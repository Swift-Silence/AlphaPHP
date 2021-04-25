<?php

namespace Alpha\Core;



use \Alpha\Core\Config;

use \Alpha\Debug\Logger;

use \Alpha\Router\Router;

class App {

    private $Router;

    public function __construct() {
        Logger::log($this, "Application object instantiated.");

        $this->Router = new Router();
    }

}
