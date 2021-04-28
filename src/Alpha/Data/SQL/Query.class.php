<?php

namespace Alpha\Data\SQL;

use \Alpha\Debug\Logger;



class Query
{

    const ATTR_TYPE_INSERT = 1;
    const ATTR_TYPE_SELECT = 2;

    private $type;
    private $table_name;
    private $data = [];
    private $where;

    public function __construct($type, $table_name, $data, $where = false)
    {
        $this->type = $type;
        $this->table_name = $table_name;
        $this->data = $data;
        $this->where = $where;

        $this->log("New query object created.");
    }

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

    private function SQL_Insert()
    {
        $data = $this->parseData();

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

    private function parseData()
    {
        $data = [];
        $data['cols']         = [];
        $data['vals']         = [];

        foreach ($this->data as $col => $val)
        {
            $data['cols'][] = $col;
            $data['vals'][] = ":" . $col;
        }

        return $data;
    }

    private function realVal($val)
    {
        if (is_numeric($val))
        {
            return $val;
        }
        else
        {
            return "\"$val\"";
        }
    }

    private function log($message)
    {
        Logger::log($this, "<b>[{$this->table_name}]</b> $message");
    }

}
