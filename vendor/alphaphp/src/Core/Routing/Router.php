<?php

namespace AlphaPHP\Core\Routing;

/**
 * Router class
 *
 * Responsible for holding routes and executing the correct one.
 */

use \AlphaPHP\Debug\Logger;

use \AlphaPHP\Core\Networking;

use \AlphaPHP\Router\Route;

class Router
{

    /**
     * Active route object.
     * @var \Alpha\Router\Route
     */
    private $route;

    /**
     * Parameters to be passed to the controller method
     * @var array
     */
    private $params = [];

    /**
     * Holds all routes within the application.
     * @var array
     */
    private static $routes = [];

    /**
     * Logs instantiation, and performs a network redirect if a resource isn't found.
     */
    public function __construct()
    {
        Logger::log($this, "Router object instantiated.");

        if (!$this->detectRoute())
        {
            // TODO: 404 redirect
            echo '<h1>404!</h1>';
            //Logger::dump(true);

            Networking::redirect('/404');
        }
    }

    /**
     * Executes the currently active route, if it has been successfully selected.
     * @return mixed|false Result of application runtime.
     */
    public function execute()
    {
        if (is_object($this->route))
        {
            //return $this->route->execute($this->params);
            $C = $this->route->getControllerObj();
            $C->setRoute($this->route);
            return $this->route->execute($this->params);
        }
    }

    /**
     * Detects the route from the URI and checks if it is established.
     * @return boolean Whether the route was found or not
     */
    private function detectRoute()
    {
        // Grab request route and make a copy
        $request_route = explode('/', ltrim(REQUEST_URI, '/'));
        $attempt = $request_route;

        // Back-propogate through request in attempt to find out which pieces
        // are important to the route and which are to be parameters.
        foreach ($request_route as $uri_piece)
        {
            // Implode current attempt.
            $path = '/' . implode('/', $attempt);
            Logger::log($this, "Searching known routes for <strong>{$path}</strong>...");

            // Check if route exists
            if (isset(static::$routes[$path]))
            {
                // Get params by removing found path from request route, exploding the data, and filtering empty indexes out.
                $this->params = array_filter(explode('/', str_replace($path . '/', '', REQUEST_URI)));
                //die(print_r($this->params));

                // Set active route and log finding.
                $this->route = static::$routes[$path];
                Logger::log($this, "Determined route request to be <strong>{$path}</strong>.");
                Logger::log($this, "\tController Name: <b>" . $this->route->getController() . "</b>");
                Logger::log($this, "\tMethod Name: <b>" . $this->route->getAction() . "</b>");
                return true;
            }
            else
            {
                // Pops off 1 URI piece before looping to next attempt.
                array_pop($attempt);
            }
        }

        // Runs if no route is found.
        Logger::_($this, 3);
        Logger::log($this, "Route not determined from <strong>/" . REQUEST_ROUTE . "</strong>.");
        return false;
    }

    /**
     * Add routes to the application. Used in routes.inc.php to establish custom routes.
     * @param array $routes Array of \Alpha\Router\Route objects to add.
     */
    public static function add(\AlphaPHP\Core\Routing\Route ...$routes)
    {
        foreach ($routes as $route)
        {
            static::$routes[$route->getPath()] = $route;
        }
    }

}
