<?php

namespace Alpha\Data\SQL;

use \Alpha\Data\SQL\Query;
use \Alpha\Debug\Logger;

/**
 * QueryBuilder class
 *
 * Handles all query objects and forwards query SQL to the DB object.
 */

class QueryBuilder implements \Alpha\Data\SQL\QueryInterface
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
    public function __construct(\Alpha\Data\SQL\Table $Table)
    {
        $this->Table = $Table;

        Logger::log($this, "QueryBuilder object instantiated.");
    }

    /**
     * Provides easy plug to build an insert query.
     * @param  \Alpha\Data\SQL\Table $Table Table object.
     * @param  array                 $data  Data to insert into table.
     */
    public function insert(array $data)
    {
        Logger::log($this, "Building new SQL <b>INSERT</b> query...");

        $this->Query = new Query(self::ATTR_TYPE_INSERT, $this->Table->getSQLTableName(), $data);
        return $this->Stmt = $this->Table->DB->query($this->Query->getSQL(), $data);
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
    public function select(array $selectors, array &$where, $order_by, $limit)
    {
        Logger::log($this, "Building new SQL <b>SELECT</b> query...");

        $this->Query = new Query(self::ATTR_TYPE_SELECT, $this->Table->getSQLTableName(), $selectors, $where, $order_by, $limit);

        //die($this->Query->getSQL());
        return $this->Stmt = $this->Table->DB->query($this->Query->getSQL(), $this->Query->placeholders);
    }

    /**
     * Provides easy plug to build an update query.
     * @param  \Alpha\Data\SQL\Table $Table The table object.
     * @param  array  $data  Data to be updated
     * @param  array  $where Where to update the data.
     * @return \PDOStatement result of the query
     */
    public function update($data, $where)
    {
        Logger::log($this, "Building new SQL <b>UPDATE</b> query...");

        $this->Query = new Query(self::ATTR_TYPE_UPDATE, $this->Table->getSQLTableName(), $data, $where);
        //die($this->Query->getSQL());
        return $this->Stmt = $this->Table->DB->query($this->Query->getSQL(), $this->Query->placeholders);
    }

    /**
     * Provides easy plug to build a DELETE query.
     * @param  \Alpha\Data\SQL\Table $Table The table object
     * @param  array $where Where to delete the data
     * @return \PDOStatement result of the query
     */
    public function delete($where)
    {
        Logger::log($this, "Building new SQL <b>DELETE</b> query...");

        $this->Query = new Query(self::ATTR_TYPE_DELETE, $this->Table->getSQLTableName(), [], $where);
        //die($this->Query->getSQL());
        return $this->Stmt = $this->Table->DB->query($this->Query->getSQL(), $this->Query->placeholders);
    }

}
