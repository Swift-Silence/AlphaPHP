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
        return $Table->DB->query($this->Query->getSQL(), $data);
    }

    public function select($Table, $selectors, &$where, $order_by, $limit)
    {
        Logger::log($this, "Building new SQL <b>SELECT</b> query...");

        $this->Query = new Query(Query::ATTR_TYPE_SELECT, $Table->getSQLTableName(), $selectors, $where, $order_by, $limit);

        $this->fixWhere($where);
        $this->Stmt = $Table->DB->query($this->Query->getSQL(), $where);
        return $this->Stmt->fetchAll();
    }

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
