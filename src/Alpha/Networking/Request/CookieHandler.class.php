<?php

namespace Alpha\Networking\Request;



use \Alpha\Debug\Logger;

class CookieHandler
{

    private $COOKIE = [];

    public function __construct()
    {
        Logger::log($this, "Cookie handler object instantiated.");

        $this->readCookieData();
    }

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
