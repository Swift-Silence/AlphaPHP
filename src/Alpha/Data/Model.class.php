<?php

namespace Alpha\Data;

/**
 * Model class
 *
 * All models extend this class, which provides easy access for Model developers
 * to certain functions.
 */

use \Alpha\Data\SQL\Table;

use \Alpha\Debug\Logger;

class Model
{

    /**
     * Database table object
     * @var \Alpha\Data\SQL\Table|null
     */
    protected $Table;

    /**
     * Must run before any child model constructor code runs.
     * @param boolean $create_table Whether or not this model should create and represent a database table.
     */
    public function __construct($create_table = false)
    {
        Logger::log($this, "Initializing...");

        if ($create_table) $this->Table = new Table();
    }

}
