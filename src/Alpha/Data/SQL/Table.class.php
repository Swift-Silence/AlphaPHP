<?php

namespace Alpha\Data\SQL;

/**
 * Represents a whole database table and can perform actions such as retrieving
 * and inserting data.
 */

use \Alpha\Debug\Logger;

class Table
{

    /**
     * Mainly here for logging that the class instantiated properly.
     */
    public function __construct()
    {
        Logger::log($this, "New Table object created.");
    }

}
