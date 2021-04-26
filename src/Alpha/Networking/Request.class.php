<?php

namespace Alpha\Networking;



use \Alpha\Debug\Logger;

class Request
{

    private $POST = [];
    private $GET = [];

    public function __construct()
    {
        Logger::log($this, "Request object instantiated.");

        $this->getRequests();
    }

    public function get($var)
    {
        return (isset($this->GET[$var])) ? $this->GET[$var] : false;
    }

    public function post($var)
    {
        return (isset($this->POST[$var])) ? $this->POST[$var] : false;
    }

    private function getRequests()
    {
        Logger::log($this, "Reading <b>POST</b> data...");
        foreach ($_POST as $var => $val)
        {
            Logger::log($this, "<i>POST</i><b>['{$var}' => '$val']</b>");
            $this->POST[$var] = $val;
        }

        Logger::log($this, "Reading <b>GET</b> data...");
        foreach ($_GET as $var => $val)
        {
            Logger::log($this, "<i>GET</i><b>['{$var}' => '$val']</b>");
            $this->GET[$var] = $val;
        }
    }

}
