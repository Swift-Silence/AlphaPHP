<?php

namespace AlphaPHP\Core\Networking\Request;

/**
 * CookieHandler class
 */

use \AlphaPHP\Debug\Logger;

class CookieHandler
{

    /**
     * Holds all cookie data
     * @var array
     */
    private $COOKIE = [];

    /**
     * Logs instantiation and then reads the cookie data.
     */
    public function __construct()
    {
        Logger::log($this, "Cookie handler object instantiated.");

        $this->readCookieData();
    }

    /**
     * Reads all cookie data and logs all findings.
     */
    private function readCookieData()
    {
        Logger::log($this, "Reading <b>COOKIE</b> data...");
        foreach ($_COOKIE as $var => $val)
        {
            $this->COOKIE[$var] = $val;
            Logger::log($this, "<i>COOKIE</i><b>['{$var}' => '{$val}']</b>");
        }
    }

}
