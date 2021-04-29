<?php

namespace Alpha\Data\SQL;

use \Alpha\Debug\Logger;

/**
 * Query class
 *
 * Represents a query in the code and can spit out SQL syntax based on query type.
 */

class Query
{

    // Constants for testing query type
    const ATTR_TYPE_INSERT = 1;
    const ATTR_TYPE_SELECT = 2;

    /**
     * Numerical representation of the query type
     * @var int
     */
    private $type;

    /**
     * SQL table name
     * @var string
     */
    private $table_name;

    /**
     * Holds data being inserted or changed
     * @var array
     */
    private $data = [];

    /**
     * Holds where clause as array
     * @var array|null
     */
    private $where;

    /**
     * Loads dependencies
     * @param int           $type       Query type.
     * @param string        $table_name SQL table name.
     * @param array|false   $data       Data.
     * @param array|false   $where      Where clause as array
     */
    public function __construct($type, $table_name, $data = false, $where = false)
    {
        $this->type = $type;
        $this->table_name = $table_name;
        $this->data = $data;
        $this->where = $where;

        $this->log("New query object created.");
    }

    /**
     * Returns SQL syntax of this query
     * @return string SQL query
     */
    public function getSQL()
    {
        switch ($this->type)
        {
            case Query::ATTR_TYPE_INSERT:
                return $this->SQL_insert();
                break;
            default:
                throw new \Alpha\Exceptions\Exception("Invalid query type passed to " . __CLASS__ . " object.");
                break;
        }
    }

    /**
     * Returns SQL query for INSERT INTO statement.
     */
    private function SQL_Insert()
    {
        $data = $this->parseData(); // Get data in PDO placeholder format.

        $SQL = "INSERT INTO `{$this->table_name}` (";

            foreach ($data['cols'] as $col)
            {
                $SQL .= "$col, ";
            }

        $SQL = substr(rtrim($SQL), 0, -1) . ") VALUES ("; #trim space and comma from last entry

            foreach ($data['vals'] as $val)
            {
                $SQL .= "$val, ";
            }

        $SQL = substr(rtrim($SQL), 0, -1) . ")";

        return $SQL;
    }

    /**
     * Parses the data array to be used in PDO with placeholders.
     * @return array Placeholder data
     */
    private function parseData()
    {
        $data = [];
        $data['cols']         = [];
        $data['vals']         = [];

        foreach ($this->data as $col => $val)
        {
            $data['cols'][] = $col; // ie. username
            $data['vals'][] = ":" . $col; // ie. :username
        }

        return $data;
    }

    /**
     * Private logging function for quick access and table name association.
     * @param  string $message Message to send to the logger.
     */
    private function log($message)
    {
        Logger::log($this, "<b>[{$this->table_name}]</b> $message");
    }

}
