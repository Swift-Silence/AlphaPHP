<?php

namespace Alpha\Data\SQL\Table;

use \Alpha\Debug\Logger;

/**
 * TableBuilder class
 *
 * Primarily used by a specific method within Models to design a table in code and then
 * sends that data to the DB object for translation into SQL syntax.
 */

class TableBuilder
{

    /**
     * Table object
     * @var \Alpha\Data\SQL\Table
     */
    private $Table;

    /**
     * Database object
     * @var \Alpha\Data\SQL\DB
     */
    private $DB;

    /**
     * SQL Table name
     * @var string
     */
    private $name;

    /**
     * Holds all table column objects in a single array.
     * @var array
     */
    private $cols = [];

    /**
     * Initialize and establish properties
     * @param Alpha\Data\SQL\Table $Table Table object
     * @param Alpha\Data\SQL\DB    $DB    Database object
     */
    public function __construct(\Alpha\Data\SQL\Table $Table, \Alpha\Data\SQL\DB $DB)
    {
        $this->Table = $Table;
        $this->DB = $DB;
        $this->name = $this->Table->getSQLTableName();
        $this->log("Table builder has been created!");
    }

    /**
     * Adds a new column to the table design.
     * @param  string  $name    Name of the column
     * @param  string  $type    Type of data held in column
     * @param  mixed   $values  Length/Values attribute of column
     * @param  boolean $null    Null option for column
     * @param  boolean $primary Whether column is primary key or not
     * @param  boolean $AI      If column should auto increment
     */
    public function col($name, $type, $values = null, $null = false, $primary = false, $AI = false)
    {
        $this->log("Column <b>`$name` | </b>Type: <b>$type | </b>Length/Values: <b>$values | </b>Null: <b>$null | </b>Primary: <b>$primary | </b>Auto Increment: <b>$AI</b>");
        $this->cols[] = (object) [
            'name' => $name,
            'type' => $type,
            'values' => $values,
            'null' => $null,
            'primary' => $primary,
            'AI' => $AI
        ];
        //die("<pre>" . print_r($this->cols));
    }

    /**
     * Builds the table by using the DB object's createTable() method.
     * @return true if build executes successfully.
     */
    public function execute()
    {
        $this->log("Attempting to create MySQL table...");

        try {
            $this->DB->createTable($this->name, $this->cols);
        } catch (\Throwable $e) {
            $this->log("Exception thrown in <b>" . $e->getFile() . "</b> line <b>" . $e->getLine() . "</b>. <i>" . $e->getMessage() . "</i>");
            Logger::dump(true);
        }

        $this->log("Table <b>`{$this->name}`</b> created successfully.");
        return true;
    }

    /**
     * Private log function for quick use.
     * @param  string $message Message to send to the logger.
     */
    private function log($message)
    {
        Logger::log($this, "<b>[{$this->name}]</b> $message");
    }

}
