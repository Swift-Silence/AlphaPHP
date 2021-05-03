<?php

namespace AlphaPHP;

/**
 * Model class
 *
 * All models extend this class, which provides easy access for Model developers
 * to certain functions.
 */

use \AlphaPHP\Core\Model\Data\DB;
use \AlphaPHP\Core\Model\Data\SQL\Table\TableManager;
use \AlphaPHP\Debug\Logger;



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

    public function setRequestHandler(\AlphaPHP\Core\Networking\Request $Request)
    {
        $this->Request = $Request;
    }

}
