<?php

namespace AlphaPHP\Core\Routing;

/**
 * Route class
 *
 * Object to link a URL path to a Controller/Action combo.
 */

use \AlphaPHP\Debug\Logger;

use \AlphaPHP\Exceptions\RouteFormatException;

class Route
{

    /**
     * Path/to/page in the URL, relative to installation directory (ie. /home/index instead of site.com/home/index)
     * @var string
     */
    private $path;

    /**
     * Name of the controller to link in namespaced format using either \\ or .
     * @var string
     */
    private $controller;

    /**
     * Name of the method in the controller class to be linked.
     * @var string
     */
    private $action;

    /**
     * Controller object.
     * @var \Alpha\Core\Controller
     */
    private $Ctrl;

    /**
     * Checks route notation before assigning a controller and action name.
     * @param string $path  Path/to/webpage
     * @param array  $route Route array in ['Controller' => 'action'] notation.
     */
    public function __construct($path, array $route)
    {
        // Check route notation
        if (count($route) > 1 || count($route) < 1)
        {
            throw new RouteFormatException("Routes should be formatted as <strong>['Controller' => 'Action']</strong>. Something is wrong with the route declaration for <strong>{$path}</strong> in <strong>config/routes.inc.php</strong>");
        }

        $this->path = $path;

        // Get route from route notation
        foreach ($route as $c => $a)
        {
            $this->controller = str_replace('.', '\\', $c); // Also checks for (.) dot notation
            $this->action = $a;
        }

        Logger::log($this, "Route <strong>{$path}</strong> --> <strong>['{$this->controller}' => '{$this->action}']</strong> created.");
    }

    /**
     * Returns the path for this route.
     * @return string The path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the controller name.
     * @return string Controller name
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Gets the action name.
     * @return string Action name
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Executes the route. Called by the framework Router when its execute function
     * is called.
     * @param  array  $params Parameters to pass to the controller method.
     * @return mixed|false    Result of the controller runtime.
     */
    public function execute($params)
    {
        Logger::log($this, "Attempting to instantiate controller...");

        $controller = "Controllers\\" . $this->controller;

        // Instantiate object, and log errors on failure.
        try {
            $this->Ctrl = new $controller();
        } catch (\Throwable $e) {
            Logger::log($this, "[<b>{$this->path}</b>] Unable to instantiate controller <b>{$this->controller}</b>: " . $e->getMessage() . " [" . $e->getFile() . ":" . $e->getLine() . "]");
            Logger::dump(1);
        }

        // Use this function to call controllers dynamically
        return call_user_func_array([$this->Ctrl, $this->action], $params);
    }

}
