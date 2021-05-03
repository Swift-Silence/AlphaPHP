<?php

namespace AlphaPHP\Core;

/**
 * \Alpha\Core\Application class
 *
 * Begins the application processes
 */

use \AlphaPHP\Core\Config;
use \AlphaPHP\Core\Routing\Router;
use \AlphaPHP\Debug\Logger;

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
