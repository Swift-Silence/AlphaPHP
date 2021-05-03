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
     * Model manager object
     * @var \Alpha\Core\ModelManager
     */
    protected $Model;

    /**
     * Holds all variables to be used in the frontend view.
     * @var array
     */
    private $vars = [];

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
     * Stores a variable for use in the frontend view.
     * 
     * @param string $name Name of the variable. 
     * @param mixed  $val  Value to be stored.
     */
    protected function var(string $name, $val)
    {
        $this->vars[$name] = $val;
    }

    protected function view(string $path = null)
    {
        
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
