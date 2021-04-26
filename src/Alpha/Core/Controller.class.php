<?php

namespace Alpha\Core;



use \Alpha\Debug\Logger;

use \Alpha\Networking\Request;

class Controller
{

    protected $Request;

    public function __construct()
    {
        Logger::log(__CLASS__, "Controller instantiated.");

        Logger::log(__CLASS__, "Loading in dependencies...");
        $this->Request = new Request();
    }

    protected function log($message)
    {
        Logger::log($this, $message);
    }

}
