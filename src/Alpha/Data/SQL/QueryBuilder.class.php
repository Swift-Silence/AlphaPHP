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
        $Table->DB->query($this->Query->getSQL(), $data);
    }

}
