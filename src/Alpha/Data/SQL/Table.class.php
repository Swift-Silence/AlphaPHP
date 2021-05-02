<?php

namespace Alpha\Data\SQL;

use \Alpha\Debug\Logger;
use \Alpha\Data\SQL\DB;
use \Alpha\Data\SQL\Exceptions\TableDesignException;
use \Alpha\Data\SQL\Table\TableBuilder;
use \Alpha\Data\SQL\Query;
use \Alpha\Data\SQL\QueryBuilder;

/**
 * Represents a whole database table and can perform actions such as retrieving
 * and inserting data.
 */

class Table
{

    /**
     * Database object
     * @var \Alpha\Data\SQL\DB
     */
    public $DB;

    /**
     * Table name
     * @var string
     */
    private $name;

    /**
     * Options
     * @var array
     */
    private $options = [
        'table_prefix' => ''
    ];

    /**
     * Model object
     * @var \Alpha\Data\Model
     */
    private $Model;

    /**
     * QueryBuilder object
     * @var \Alpha\Data\SQL\QueryBuilder
     */
    private $Query;

    /**
     * Mainly here for logging that the class instantiated properly.
     */
    public function __construct($name, $Model, $options)
    {
        $this->options = array_merge($this->options, $options); // Merge options with defaults

        $this->name = $name;

        // Acquire dependencies
        $this->Model = $Model;
        $this->DB    = DB::singleton();
        $this->Query = new QueryBuilder();

        $this->log("Table object instantiated.");

        $build_method = 'table_build_' . $name; // This method will be checked for if table doesn't already exist

        $this->log("Checking if table already exists in the database...");
        if (!$this->exists())
        {
            $this->log("Table not found in SQL database. Attempting to create table <b>`{$this->name}`</b>...");

            $this->log("Checking if model <b>[" . get_class($this->Model) . "]</b> has <b>$build_method</b> method...");
            if (!method_exists($this->Model, $build_method)) // Check for method in the model
                throw new TableDesignException("Method <b>$build_method</b> not present in Model <b>[" . get_class($this->Model) . "]</b>.");

            $Builder = new TableBuilder($this, $this->DB); // Instantiate the table builder
            $this->Model->$build_method($Builder); // Pass builder to Model build method
            $Builder->execute(); // Build the designed table
        }
        else
        {
            $this->log("Found table!"); // Only runs if the table is found and did not have to be created
        }

        $this->log("Table linked successfully.");
    }

    /**
     * Returns the SQL table name, including the prefix if the table has one.
     * @return string SQL table name
     */
    public function getSQLTableName()
    {
        return "{$this->options['table_prefix']}{$this->name}";
    }

    /**
     * Inserts data into the table
     * @param  array  $data Array of data in ['column' => 'value'] format.
     */
    public function insert(array $data)
    {
        $this->checkModelBefore($data);

        $this->log("Inserting data into database: <b>[{{data}}]</b>", true, $data);
        $this->Query->insert($this, $data);
    }

    /**
     * Selects data from the table and returns array of objects representing table rows.
     * @param  array            $selectors Array of selectors to use
     * @param  array|false      $where     Where array or false.
     * @param  array|false      $order_by  Order by array or false.
     * @param  int|string|false $limit     int, string, or false.
     * @return array rows
     */
    public function select($selectors, $where = false, $order_by = false, $limit = false)
    {
        $this->log("Selecting data from the database... <b>[{{selectors}}]</b> [WHERE <b>{{where}}</b>]", true, [], $where, $selectors);
        $results = $this->Query->select($this, $selectors, $where, $order_by, $limit);

        $this->checkModelAfter($results);

        return $results;
    }

    /**
     * Updates data in the table
     * @param  array       $data  Data to be updated
     * @param  array|false $where Where to update data
     */
    public function update($data, $where = false)
    {
        $this->checkModelBefore($data);

        $this->log("Updating data in the database... <b>[{{data}}]</b> [WHERE <b>{{where}}</b>]", true, $data, $where);
        $this->Query->update($this, $data, $where);
    }

    /**
     * Deletes data from the table
     * @param  array $where  Where to dete the data
     */
    public function delete($where)
    {
        $this->log("Deleting data from the database... [WHERE <b>{{where}}</b>]", true, [], $where);
        $this->Query->delete($this, $where);
    }

    /**
     * Checks if the table exists in the database using the DB object
     * @return bool
     */
    private function exists()
    {
        try {
            return (bool) $this->DB->hasTable($this->getSQLTableName());
        } catch (\Throwable $e) {
            $this->log("Error thrown in <b>" . $e->getFile() . "</b> line <b>" . $e->getLine() . "</b>. <i>" . $e->getMessage() . "</i>");
        }
    }

    /**
     * Checks the model for methods pertaining to data alteration before insertion
     * into the MySQL table.
     * @param  array $data  References the data being inserted in order to alter values.
     */
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

    /**
     * Checks the model for methods pertaining to data alteration after retrieval
     * from the MySQL table.
     * @param  array  $data Data to be modified
     * @return void        void
     */
    private function checkModelAfter(&$data)
    {
        foreach ($data as $i => $row)
        {
            foreach ($row as $col => $val)
            {
                $method_name = "{$this->name}_table_after_{$col}";
                //die($method_name);

                if (method_exists($this->Model, $method_name))
                {
                    $data[$i][$col] = $this->Model->$method_name($val);
                }
            }
        }
    }

    /**
     * Private logging method for efficient use.
     * @param  string  $message  Message to send to the logger.
     * @param  boolean $db_alter Whether log came from a method that alters the database or not
     * @param  array   $data     Data to print to log if parameter 2 is true.
     */
    private function log($message, $db_alter = false, $data = [], $where = [], $selectors = [])
    {
        $method = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];

        if ($db_alter)
        {
            $s_data = "";
            $s_where = "";
            $s_selectors = "";

            foreach ($data as $col => $val)
            {
                $s_data .= "'{$col}' => '{$val}', ";
            }
            foreach ($where as $col => $val)
            {
                $s_where .= "{$col}<i>{$val}</i>";
            }
            foreach ($selectors as $sel)
            {
                if ($sel == Query::ATTR_SELECT_ALL)
                {
                    $s_selectors = "*";
                    continue;
                }
                else
                {
                    $s_selectors .= "`{$sel}`, ";
                }
            }

            $s_data = substr(rtrim($s_data), 0, -1);
            if ($s_selectors != "*") $s_selectors = substr(rtrim($s_selectors), 0, -1); // Trim last comma if * not used

            $find = ['{{data}}', '{{where}}', '{{selectors}}'];
            $replace = [$s_data, $s_where, $s_selectors];

            Logger::log($this, "<b>[{$this->name}]</b> " . str_replace($find, $replace, $message)); // <i>[" . __CLASS__ . "::{$method}]</i>
        }
        else
        {
            Logger::log($this, "<b>[{$this->name}]</b> $message"); // <i>[" . __CLASS__ . "::{$method}]</i>
        }
    }

}
