<?php

namespace Alpha\Router;

use \Alpha\Debug\Logger;

use \Alpha\Networking;

use \Alpha\Router\Route;

class Router
{

    private $route;
    private $params = [];

    private static $routes = [];

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

    public function execute()
    {
        if (is_object($this->route))
        {
            return $this->route->execute($this->params);
        }
    }

    private function detectRoute()
    {
        $request_route = explode('/', REQUEST_ROUTE);
        $attempt = $request_route;

        foreach ($request_route as $uri_piece)
        {
            $path = '/' . implode('/', $attempt);
            Logger::log($this, "Searching known routes for <strong>{$path}</strong>...");

            if (isset(static::$routes[$path]))
            {
                $this->params = array_filter(explode('/', str_replace($path . '/', '', '/' . REQUEST_ROUTE)));
                //die(print_r($this->params));

                $this->route = static::$routes[$path];
                Logger::log($this, "Determined route request to be <strong>{$path}</strong>.");
                Logger::log($this, "\tController Name: <b>" . $this->route->getController() . "</b>");
                Logger::log($this, "\tMethod Name: <b>" . $this->route->getAction() . "</b>");
                return true;
            }
            else
            {
                array_pop($attempt);
            }
        }

        Logger::_($this, 3);
        Logger::log($this, "Route not determined from <strong>/" . REQUEST_ROUTE . "</strong>.");
        return false;
    }

    public static function add(\Alpha\Router\Route ...$routes)
    {
        foreach ($routes as $route)
        {
            static::$routes[$route->getPath()] = $route;
        }
    }

}
