<?php

namespace AlphaPHP\Core\Model\Data\SQL\Table;

use \AlphaPHP\Core\Model\Data\SQL\Table;
use \AlphaPHP\Debug\Logger;

/**
 * TableManager class
 *
 * Manages table access and creation per Model.
 */

class TableManager
{

    /**
     * Holds all created tables in 1 array
     * @var array
     */
    private $tables = [];

    /**
     * Mainly here for logging purposes.
     */
    public function __construct()
    {
        Logger::log($this, "Table manager instantiated.");
    }

    /**
     * Creates a new table object.
     * @param  string            $table_name Name of the table.
     * @param  \Alpha\Data\Model $Model      Model object.
     * @param  array             $options    Array of options that should be passed to the table object.
     */
    public function create($table_name, $Model, $options = [])
    {
        Logger::log($this, "Creating new table object with name [<b>$table_name</b>]...");

        try
        {
            $this->tables[$table_name] = new Table($table_name, $Model, $options);
        }
        catch (\AlphaPHP\Exceptions\Exception $e)
        {
            $e->dump(); // Automatically dump any framework exceptions.
        }

        return $this->tables[$table_name];
    }

    /**
     * Returns a table or false if it has not been instantiated.
     * @param  string $table_name Array index of table object.
     * @return \Alpha\Data\SQL\Table|false
     */
    public function get($table_name)
    {
        return (isset($this->tables[$table_name])) ? $this->tables[$table_name] : false;
    }

}
