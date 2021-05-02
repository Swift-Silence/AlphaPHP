<?php

namespace Alpha\Data\SQL;

use \Alpha\Debug\Logger;

use \Alpha\Exceptions\Data\SQL\Where\SyntaxException;

/**
 * Query class
 *
 * Represents a query in the code and can spit out SQL syntax based on query type.
 */

class Query
{

    // Constants for testing query type
    const ATTR_TYPE_INSERT = 100;
    const ATTR_TYPE_SELECT = 101;
    const ATTR_TYPE_UPDATE = 102;
    const ATTR_TYPE_DELETE = 103;

    // Special Select all constant
    const ATTR_SELECT_ALL = 1000;




    /**
     * Holds all data and where data in PDO placeholder format
     * @var array
     */
    public $placeholders = [];

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
     * Array of acceptable symbols for indicating AND operations in SQL WHERE
     * clauses
     * @var array
     */
    private $where_and_symbols = ['+', '&'];

    /**
     * Array of acceptable symbols for indicating OR operations in SQL WHERE
     * clauses
     * @var array
     */
    private $where_or_symbols = ['/', '|'];

    /**
     * Order by data.
     * @var array|false|null
     */
    private $order_by;

    /**
     * Limit data
     * @var int|string|null
     */
    private $limit;

    /**
     * Loads dependencies
     * @param int           $type       Query type.
     * @param string        $table_name SQL table name.
     * @param array|false   $data       Data.
     * @param array|false   $where      Where clause as array
     * @param array|false   $order_by   SQL ORDER BY array (ie. ['username' => 'asc'])
     * @param mixed         $limit      SQL LIMIT string or number
     */
    public function __construct($type, $table_name, $data = false, $where = false, $order_by = false, $limit = false)
    {
        $this->type = $type;
        $this->table_name = $table_name;
        $this->data = $data;
        $this->where = $where;
        $this->order_by = $order_by;
        $this->limit = $limit;

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
            case Query::ATTR_TYPE_INSERT: // INSERT INTO queries
                return $this->SQL_insert();
            case Query::ATTR_TYPE_SELECT: // SELECT queries
                return $this->SQL_select();
            case Query::ATTR_TYPE_UPDATE: // UPDATE queries
                return $this->SQL_update();
            case Query::ATTR_TYPE_DELETE:
                return $this->SQL_delete();
            default:
                throw new \Alpha\Exceptions\Exception("Invalid query type passed to " . __CLASS__ . " object.");
        }
    }

    /**
     * Returns SQL query for INSERT INTO statement.
     */
    private function SQL_Insert()
    {
        $data = $this->parseInsertData(); // Get data in PDO placeholder format.

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
     * Returns SQL query for SELECT statement.
     */
    private function SQL_select()
    {
        $SQL = "SELECT ";

        $all = false;
        foreach ($this->data as $col)
        {
            if ($col == Query::ATTR_SELECT_ALL) {
                $SQL .= "* ";
                $all = true;
                continue;
            }

            $SQL .= "`{$col}`, ";
        }
        $SQL = rtrim($SQL);
        if (!$all) $SQL = substr($SQL, 0, -1); // Trim last comma.

        $SQL .= " FROM `{$this->table_name}` " . $this->parseWhere();

        if ($this->order_by) $SQL .= $this->parseOrderBy();
        if ($this->limit) $SQL .= $this->parseLimit();

        //die($SQL);
        return trim($SQL);
    }

    /**
     * Returns SQL query for UPDATE statement
     */
    private function SQL_update()
    {
        $SQL = "UPDATE `{$this->table_name}` SET ";

        $SQL .= $this->parseUpdateData() . $this->parseWhere();

        //print_r($this);
        return trim($SQL);
    }

    /**
     * Returns SQL query for DELETE statement.
     */
    private function SQL_delete()
    {
        $SQL = "DELETE FROM `{$this->table_name}` " . $this->parseWhere();

        return trim($SQL);
    }

    /**
     * Parses the data array to be used in PDO with placeholders.
     * @return array Placeholder data
     */
    private function parseInsertData()
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
     * Parses update data and sends it back as a string. Differs from parseInsertData
     * as that function is intended to separate column names and values and returns
     * an array rather than a string.
     * @return string Formatted SQL UPDATE definitions.
     */
    private function parseUpdateData()
    {
        $SQL = "";

        foreach ($this->data as $col => $val)
        {
            $col_ph = $this->addPlaceholder($col, $val)->col;

            $SQL .= "`{$col}`=:{$col_ph}, ";
        }
        $SQL = substr(rtrim($SQL), 0, -1).  " ";

        return $SQL;
    }

    /**
     * Adds a placeholder to the placeholders property and also fixes the issue
     * of duplicates in the case where you need to update the same column you
     * use to isolate a set of rows.
     * @param string $col Column name.
     * @param mixed  $val Value of $col
     * @return \stdClass Object representing the column name and original value.
     */
    private function addPlaceholder($col, $val)
    {
        if (isset($this->placeholders[$col])) $col = $col . (string)mt_rand();
        //echo "$col\t";
        $this->placeholders[$col] = $val;
        return (object) ['col' => $col, 'val' => $val];
    }

    /**
     * Parses the SQL where clause and uses the built-in addPlaceholder function
     * to build up the placeholder data.
     * @return string Formatted SQL
     */
    private function parseWhere()
    {
        if (!$this->where || empty($this->where)) return "";
        $where_data = $this->where;

        $SQL = "WHERE ";

        $i = 0;
        foreach ($where_data as $col => $val)
        {
            if ($i == 0) $SQL .= $this->parseWhereCondition($col, $val, true) . " ";
            else $SQL .= $this->parseWhereCondition($col, $val) . " ";

            $i++;
        }

        return trim($SQL);
    }

    /**
     * Parses the SQL ORDER BY clause.
     * @return string Formatted SQL
     */
    private function parseOrderBy()
    {
        if (!$this->order_by) return ""; // Return empty string if not set to true

        $s = "ORDER BY ";
        foreach ($this->order_by as $col => $order)
        {
            $s .= "`{$col}` " . strtoupper($order) . " ";
        }

        return $s;
    }

    /**
     * Parses the SQL LIMIT clause
     * @return string Formatted SQL
     */
    private function parseLimit()
    {
        if (!$this->limit) return "";

        return "LIMIT " . $this->limit . " ";
    }

    /**
     * Parses the where condition based on what was passed in the where array.
     *
     * Arrays for the SQL where clause have a very specific design:
     * array(
     *      '<column_name>' => '[<|>|=]<value>',
     *      '[+|/]<column_name>' => '[<|>|=]<value>'
     * );
     *
     * Only the first index title does not require a leading &\+ or /\|.
     *
     * @param  string  $col   Column name
     * @param  mixed   $val   Value of the column
     * @param  boolean $first If this is the first element in the where array
     * @return string Formatted SQL
     */
    private function parseWhereCondition($col, $val, $first = false)
    {
        $s = "";

        if (ctype_alpha(substr($col, 0, 1)) && !$first) // Check if first val is alphanumeric and if $first is false
        {
            throw new SyntaxException("There is an issue with your WHERE array syntax. Any column names after the first index must have an AND (+, &) or OR (/, |) symbol present as the first character.");
        }

        if (in_array(substr($col, 0, 1), $this->where_and_symbols))
        {
            $s .= "AND ";
        }

        if (in_array(substr($col, 0, 1), $this->where_or_symbols))
        {
            $s .= "OR ";
        }

        if(ctype_alpha(substr($val, 0, 1))) // Check if first char in val is alphanumeric.
        {
            throw new SyntaxException("There is an issue with your where array syntax. All values being checked for must begin with >, <, or =");
        }

        $op = substr($val, 0, 1); // Get operation from first char of value var

        if (!$first)
            $col = substr($col, 1); // Remove leading symbol

        $val = ctype_alnum(substr($val, 0, 1)) ? $val : substr($val, 1);
        $val = ":" . $this->addPlaceholder($col, $val)->col; // PDO Placeholder format

        $s .= "`{$col}`{$op}{$val}";

        return $s;
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
