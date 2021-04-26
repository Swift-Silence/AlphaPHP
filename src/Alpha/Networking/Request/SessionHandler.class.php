<?php

namespace Alpha\Networking\Request;



use Alpha\Debug\Logger;

class SessionHandler
{

    private $SESSION = [];

    public function __construct()
    {
        Logger::log($this, "Session handler object instantiated.");

        $this->readSessionData();
    }

    private function readSessionData()
    {
        Logger::log($this, "Reading <b>SESSION</b> data...");
        foreach ($_SESSION as $var => $val)
        {
            $this->SESSION[$var] = $val;
            Logger::log($this, "<i>SESSION</i><b>['{$var}' => '{$val}']</b>");
        }
    }

}
