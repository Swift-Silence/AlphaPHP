<?php

namespace Alpha\Data\SQL;

use \Alpha\Debug\Logger;
use \Alpha\Data\SQL\DB;
use \Alpha\Data\SQL\Exceptions\TableDesignException;
use \Alpha\Data\SQL\Table\TableBuilder;
use \Alpha\Data\SQL\QueryBuilder;

/**
 * Represents a whole database table and can perform actions such as retrieving
 * and inserting data.
 */

class Table
{

    public $DB;

    private $name;

    private $options = [
        'table_prefix' => ''
    ];

    private $Model;

    private $Query;

    /**
     * Mainly here for logging that the class instantiated properly.
     */
    public function __construct($name, $Model, $options)
    {
        $this->options = array_merge($this->options, $options);

        $this->name = $name;

        $this->Model = $Model;
        $this->DB    = DB::singleton();
        $this->Query = new QueryBuilder();

        $this->log("Table object instantiated.");

        $build_method = 'table_build_' . $name;

        $this->log("Checking if table already exists in the database...");
        if (!$this->exists())
        {
            $this->log("Table not found in SQL database. Attempting to create table <b>`{$this->name}`</b>...");

            $this->log("Checking if model <b>[" . get_class($this->Model) . "]</b> has <b>$build_method</b> method...");
            if (!method_exists($this->Model, $build_method))
                throw new TableDesignException("Method <b>$build_method</b> not present in Model <b>[" . get_class($this->Model) . "]</b>.");

            $Builder = new TableBuilder($this, $this->DB);
            $this->Model->$build_method($Builder);
            $Builder->execute();
        }
        else
        {
            $this->log("Found table!");
        }

        $this->log("Table linked successfully.");
    }

    public function getSQLTableName()
    {
        return "{$this->options['table_prefix']}{$this->name}";
    }

    public function insert(array $data)
    {
        $this->checkModelBefore($data);

        $this->log("Inserting data into database: <b>[{{data}}]</b>", true, $data);
        $this->Query->insert($this, $data);
    }

    private function exists()
    {
        try {
            return (bool) $this->DB->hasTable($this->getSQLTableName());
        } catch (\Throwable $e) {
            $this->log("Error thrown in <b>" . $e->getFile() . "</b> line <b>" . $e->getLine() . "</b>. <i>" . $e->getMessage() . "</i>");
        }
    }

    private function checkModelBefore(&$data) // Pass data by reference so that models can tweak data
    {
        foreach ($data as $col => $val)
        {
            $method_name = "{$this->name}_table_before_{$col}";

            if (method_exists($this->Model, $method_name))
            {
                $data[$col] = $this->Model->$method_name($val);
            }
        }
    }

    private function log($message, $db_alter = false, $data = [])
    {
        $method = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];

        if ($db_alter)
        {
            $s_data = "";
            foreach ($data as $col => $val)
            {
                $s_data .= "'{$col}' => '{$val}', ";
            }
            $s_data = substr(rtrim($s_data), 0, -1);

            Logger::log($this, "<b>[{$this->name}]</b> " . str_replace("{{data}}", $s_data, $message)); // <i>[" . __CLASS__ . "::{$method}]</i>
        }
        else
        {
            Logger::log($this, "<b>[{$this->name}]</b> $message"); // <i>[" . __CLASS__ . "::{$method}]</i>
        }
    }

}
