<?php

namespace Alpha\Data;

/**
 * Model class
 *
 * All models extend this class, which provides easy access for Model developers
 * to certain functions.
 */

use \Alpha\Data\SQL\DB;
use \Alpha\Data\SQL\TableManager;

use \Alpha\Debug\Logger;



class Model
{

    public $DB;

    /**
     * Table manager object
     * @var \Alpha\Data\SQL\TableManager
     */
    protected $Table;

    protected $Request;

    /**
     * Must run before any child model constructor code runs.
     */
    public function __construct()
    {
        Logger::log($this, "Initializing...");

        $this->DB = DB::singleton();
        $this->Table = new TableManager();
    }

    /**
     * Protected log function for ease of access by models.
     * @param  string $message Message to send to the logger.
     */
    public function log(string $message)
    {
        Logger::log($this, "$message");
    }

    public function setRequestHandler(\Alpha\Networking\Request $Request)
    {
        $this->Request = $Request;
    }

}
