<?php

namespace Alpha\Router;



use \Alpha\Debug\Logger;

use \Alpha\Exceptions\RouteFormatException;

class Route
{

    private $path;
    private $controller;
    private $action;

    private $Ctrl;

    public function __construct($path, array $route)
    {
        if (count($route) > 1 || count($route) < 1)
        {
            throw new RouteFormatException("Routes should be formatted as <strong>['Controller' => 'Action']</strong>. Something is wrong with the route declaration for <strong>{$path}</strong> in <strong>config/routes.inc.php</strong>");
        }

        $this->path = $path;

        foreach ($route as $c => $a)
        {
            $this->controller = str_replace('.', '\\', $c);
            $this->action = $a;
        }

        Logger::log($this, "Route <strong>{$path}</strong> --> <strong>['{$this->controller}' => '{$this->action}']</strong> created.");
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function execute($params)
    {
        Logger::log($this, "Attempting to instantiate controller...");

        $controller = "Controllers\\" . $this->controller;

        try {
            $this->Ctrl = new $controller();
        } catch (\Throwable $e) {
            Logger::log($this, "[<b>{$this->path}</b>] Unable to instantiate controller <b>{$this->controller}</b>: " . $e->getMessage() . " [" . $e->getFile() . ":" . $e->getLine() . "]");
            Logger::dump(1);
        }

        return call_user_func_array([$this->Ctrl, $this->action], $params);
    }

}
