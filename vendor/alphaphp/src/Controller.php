<?php

namespace AlphaPHP;

/**
 * \Alpha\Core\Controller Class
 *
 * Extended by Controllers and provides a solid hook into the application as
 * well as loading in dependencies usable by developers.
 */

use \AlphaPHP\Core\Model\ModelManager;
use \AlphaPHP\Core\Networking\Request;
use \AlphaPHP\Debug\Logger;

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

        $this->Model = new ModelManager($this->Request);

        Logger::log(__CLASS__, "Dependencies loaded! Executing application...");
        Logger::_(__CLASS__, 3);
    }

    /**
     * Provides easy access to log controller-level messages.
     * @param  string $message Message to log to the logging system.
     */
    protected function log(string $message)
    {
        $method = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
        Logger::log($this, "<b>[{$method}]</b> {$message}");
    }

}
