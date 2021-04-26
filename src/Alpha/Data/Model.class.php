<?php

namespace Alpha\Data;



use \Alpha\Data\SQL\Table;

use \Alpha\Debug\Logger;

class Model
{

    protected $Table;

    public function __construct($create_table = false)
    {
        Logger::log($this, "Initializing...");

        if ($create_table) $this->Table = new Table();
    }

}
