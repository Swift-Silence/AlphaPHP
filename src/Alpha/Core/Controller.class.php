<?php

namespace Alpha\Core;



use \Alpha\Debug\Logger;

use \Alpha\Networking\Request;
use \Alpha\Networking\Request\CookieHandler;
use \Alpha\Networking\Request\SessionHandler;

class Controller
{

    protected $Request;

    public function __construct()
    {
        Logger::log(__CLASS__, "Controller instantiated.");

        Logger::log(__CLASS__, "Loading in dependencies...");
        $this->Request = new Request();
        $this->Cookie  = new CookieHandler();
        $this->Session = new SessionHandler();
    }

    protected function log($message)
    {
        Logger::log($this, $message);
    }

}
