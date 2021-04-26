<?php

namespace Alpha\Core;

/**
 * \Alpha\Core\Controller Class
 *
 * Extended by Controllers and provides a solid hook into the application as
 * well as loading in dependencies usable by developers.
 */

use \Alpha\Core\ModelManager;

use \Alpha\Debug\Logger;

use \Alpha\Networking\Request;
use \Alpha\Networking\Request\CookieHandler;
use \Alpha\Networking\Request\SessionHandler;

class Controller
{

    /**
     * Request object
     * @var \Alpha\Networking\Request
     */
    protected $Request;

    /**
     * Cookie handler object
     * @var \Alpha\Networking\Request\CookieHandler
     */
    protected $Cookie;

    /**
     * Session handler object
     * @var \Alpha\Networking\Request\SessionHandler
     */
    protected $Session;

    /**
     * Model manager object
     * @var \Alpha\Core\ModelManager
     */
    protected $Model;

    /**
     * Initialize dependencies. All child controllers must call parent::__construct()
     * FIRST if using a constructor.
     */
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

    /**
     * Provides easy access to log controller-level messages.
     * @param  string $message Message to log to the logging system.
     */
    protected function log($message)
    {
        $method = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
        Logger::log($this, "<b>[{$method}]</b> {$message}");
    }

}
