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
use \AlphaPHP\Core\View;
use \AlphaPHP\Debug\Logger;

class Controller
{

    /**
     * Request object
     * @var \Alpha\Networking\Request
     */
    protected $Request;

    /**
     * Route object, used for getting controller and method names. 
     *
     * @var \AlphaPHP\Core\Routing\Route
     */
    protected $Route;

    /**
     * Model manager object
     * @var \Alpha\Core\ModelManager
     */
    protected $Model;

    protected $View;

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

        Logger::log(__CLASS__, "Dependencies loaded!");
    }

    /**
     * Sets the route object. We need a seperate function for this since we have to call it from the router object. 
     *
     * @param \AlphaPHP\Core\Routing\Route $Route
     * @return void
     */
    public function setRoute(\AlphaPHP\Core\Routing\Route $Route)
    {
        $this->Route = $Route;
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

    protected function setView(string $path = null)
    {
        if ($path === null) $path = $this->Route->getController() . "." . $this->Route->getAction();
        $path = str_replace(['.', '\\'], DS, $path);
        $path = VIEWS . DS . $path . '.php';
        
        $this->View = new View($path, $this->vars);
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
