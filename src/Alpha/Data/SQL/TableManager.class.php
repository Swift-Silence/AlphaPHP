<?php

namespace Alpha\Data\SQL;

use \Alpha\Debug\Logger;



class TableManager
{

    private $prefix;

    private $tables = [];

    public function __construct()
    {
        Logger::log($this, "Table manager instantiated.");
    }

    public function create($table_name, $Model, $options = [])
    {
        Logger::log($this, "Creating new table object with name [<b>$table_name</b>]...");

        try
        {
            $this->tables[$table_name] = new Table($table_name, $Model, $options);
        }
        catch (\Alpha\Exceptions\Exception $e)
        {
            $e->dump();
        }

        return $this->tables[$table_name];
    }

    public function get($table_name)
    {
        return (isset($this->tables[$table_name])) ? $this->tables[$table_name] : false;
    }

}
