<?php

namespace Alpha\Data\SQL\Table;

use \Alpha\Debug\Logger;



class TableBuilder
{

    private $Table;
    private $DB;

    private $name;
    private $cols = [];

    public function __construct(\Alpha\Data\SQL\Table $Table, \Alpha\Data\SQL\DB $DB)
    {
        $this->Table = $Table;
        $this->DB = $DB;
        $this->name = $this->Table->getSQLTableName();
        $this->log("Table builder has been created!");
    }

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

    private function log($message)
    {
        Logger::log($this, "<b>[{$this->name}]</b> $message");
    }

}
