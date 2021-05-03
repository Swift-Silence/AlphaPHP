<?php

namespace AlphaPHP\Core\Networking\Request;

/**
 * SessionHandler class
 *
 * Helps handle all session requests.
 */

use AlphaPHP\Debug\Logger;

class SessionHandler
{

    /**
     * Holds all session data.
     * @var array
     */
    private $SESSION = [];

    /**
     * Logs instantiation and reads the session data.
     */
    public function __construct()
    {
        Logger::log($this, "Session handler object instantiated.");

        $this->readSessionData();
    }

    /**
     * Reads all session data and logs their values.
     */
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
