<?php

namespace Alpha\Core;

/**
 * \Alpha\Core\Application class
 *
 * Begins the application processes
 */

use \Alpha\Core\Config;

use \Alpha\Debug\Logger;

use \Alpha\Router\Router;

class App {

    /**
     * Router object
     * @var \Alpha\Router\Router
     */
    private $Router;

    /**
     * Instantiates the router and executes the requested route.
     */
    public function __construct() {
        Logger::log($this, "Application object instantiated.");

        $this->Router = new Router();

        $this->Router->execute();
    }

}
