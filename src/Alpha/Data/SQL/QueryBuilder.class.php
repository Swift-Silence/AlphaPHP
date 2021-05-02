<?php

namespace Alpha\Data\SQL;

use \Alpha\Data\SQL\Query;
use \Alpha\Debug\Logger;

/**
 * QueryBuilder class
 *
 * Handles all query objects and forwards query SQL to the DB object.
 */

class QueryBuilder
{

    public $Stmt;

    /**
     * Holds last query object.
     * @var \Alpha\Data\SQL\Query|null
     */
    private $Query;

    /**
     * Mainly for logging purposes.
     */
    public function __construct()
    {
        Logger::log($this, "QueryBuilder object instantiated.");
    }

    /**
     * Provides easy plug to build an insert query.
     * @param  \Alpha\Data\SQL\Table $Table Table object.
     * @param  array                 $data  Data to insert into table.
     */
    public function insert($Table, $data)
    {
        Logger::log($this, "Building new SQL <b>INSERT</b> query...");

        $this->Query = new Query(Query::ATTR_TYPE_INSERT, $Table->getSQLTableName(), $data);
        return $this->Stmt = $Table->DB->query($this->Query->getSQL(), $data);
    }

    /**
     * Provides easy plug to build a select query.
     *
     * @param  \Alpha\Data\SQL\Table $Table     The table object.
     * @param  array                 $selectors Array of column to retrieve
     * @param  array                 $where     Where conditional array
     * @param  array|false           $order_by  array of 'col' => '<ASC/DESC>' or false
     * @param  mixed                 $limit     Integer or string represting SQL limit clause
     * @return array Data fetched from the database.
     */
    public function select($Table, $selectors, &$where, $order_by, $limit)
    {
        Logger::log($this, "Building new SQL <b>SELECT</b> query...");

        $this->Query = new Query(Query::ATTR_TYPE_SELECT, $Table->getSQLTableName(), $selectors, $where, $order_by, $limit);

        $this->fixWhere($where);
        $this->Stmt = $Table->DB->query($this->Query->getSQL(), $where);
        return $this->Stmt->fetchAll();
    }

    /**
     * Provides easy plug to build an update query.
     * @param  \Alpha\Data\SQL\Table $Table The table object.
     * @param  array  $data  Data to be updated
     * @param  array  $where Where to update the data.
     * @return \PDOStatement result of the query
     */
    public function update($Table, $data, $where)
    {
        Logger::log($this, "Building new SQL <b>UPDATE</b> query...");

        $this->Query = new Query(Query::ATTR_TYPE_UPDATE, $Table->getSQLTableName(), $data, $where);
        //die($this->Query->getSQL());
        return $this->Stmt = $Table->DB->query($this->Query->getSQL(), $this->Query->placeholders);
    }

    /**
     * Provides easy plug to build a DELETE query.
     * @param  \Alpha\Data\SQL\Table $Table The table object
     * @param  array $where Where to delete the data
     * @return \PDOStatement result of the query
     */
    public function delete($Table, $where)
    {
        Logger::log($this, "Building new SQL <b>DELETE</b> query...");

        $this->Query = new Query(Query::ATTR_TYPE_DELETE, $Table->getSQLTableName(), [], $where);
        //die($this->Query->getSQL());
        return $this->Stmt = $Table->DB->query($this->Query->getSQL(), $this->Query->placeholders);
    }

    /**
     * Fixes the where clause for cases of data. This make be deprecated in the future
     * due to the implementation of the public property $placeholders in the query
     * object.
     * @param  array $where Reference to the where data.
     */
    private function fixWhere(&$where)
    {
        $new = [];
        foreach ($where as $col => $val)
        {
            $c1 = substr($col, 0, 1);
            $v1 = substr($val, 0, 1);

            if (!ctype_alpha($c1)) $col = substr($col, 1);
            if (!ctype_alnum($v1)) $val = substr($val, 1);

            $new[$col] = $val;
        }

        $where = $new;
    }

}
