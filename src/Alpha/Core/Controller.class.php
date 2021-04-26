<?php

namespace Alpha\Core;



use \Alpha\Core\ModelManager;

use \Alpha\Debug\Logger;

use \Alpha\Networking\Request;
use \Alpha\Networking\Request\CookieHandler;
use \Alpha\Networking\Request\SessionHandler;

class Controller
{

    protected $Request;
    protected $Cookie;
    protected $Session;

    protected $Model;

    public function __construct()
    {
        Logger::log(__CLASS__, "Controller instantiated.");

        Logger::log(__CLASS__, "Loading in dependencies...");
        $this->Request = new Request();
        $this->Cookie  = new CookieHandler();
        $this->Session = new SessionHandler();

        $this->Model = new ModelManager();

        Logger::log(__CLASS__, "Dependencies loaded! Executing application...");
        Logger::_(__CLASS__, 3);
    }

    protected function log($message)
    {
        Logger::log($this, $message);
    }

}
