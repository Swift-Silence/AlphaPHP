<?php

namespace Alpha\Data\SQL;

use \Alpha\Data\SQL\Query;
use \Alpha\Debug\Logger;



class QueryBuilder
{

    private $Query;

    public function __construct()
    {
        Logger::log($this, "QueryBuilder object instantiated.");
    }

    public function insert($Table, $data)
    {
        Logger::log($this, "Building new SQL <b>INSERT</b> query...");

        $this->Query = new Query(Query::ATTR_TYPE_INSERT, $Table->getSQLTableName(), $data);
        $Table->DB->query($this->Query->getSQL(), $data);
    }

}
