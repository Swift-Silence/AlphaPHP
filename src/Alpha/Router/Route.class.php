<?php

namespace Alpha\Router;



use \Alpha\Debug\Logger;

use \Alpha\Exceptions\RouteFormatException;

class Route
{

    private $path;
    private $controller;
    private $action;

    public function __construct($path, array $route)
    {
        if (count($route) > 1 || count($route) < 1)
        {
            throw new RouteFormatException("Routes should be formatted as <strong>['Controller' => 'Action']</strong>. Something is wrong with the route declaration for <strong>{$path}</strong> in <strong>config/routes.inc.php</strong>");
        }

        $this->path = $path;

        foreach ($route as $c => $a)
        {
            $this->controller = $c;
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

    }

}
