<?php

namespace Alpha\Networking;

/**
 * Request class
 *
 * Provides an easy way to access GET and POST requests.
 */

use \Alpha\Debug\Logger;

class Request
{

    /**
     * Holds all POST data.
     * @var array
     */
    private $POST = [];

    /**
     * Holds all GET data.
     * @var array
     */
    private $GET = [];

    /**
     * Logs instantiation and then gets all requests.
     */
    public function __construct()
    {
        Logger::log($this, "Request object instantiated.");

        $this->getRequests();
    }

    /**
     * Gets a GET request variable.
     * @param  string $var GET variable name
     * @return mixed|false  Value of variable
     */
    public function get($var)
    {
        return (isset($this->GET[$var])) ? $this->GET[$var] : false;
    }

    /**
     * Gets a POST request variable.
     * @param  string $var POST variable name
     * @return mixed|false Value of variable
     */
    public function post($var)
    {
        return (isset($this->POST[$var])) ? $this->POST[$var] : false;
    }

    /**
     * Gets all POST and GET data and stores in their respective properties
     * while also logging the findings.
     */
    private function getRequests()
    {
        // Read POST data...
        Logger::log($this, "Reading <b>POST</b> data...");
        foreach ($_POST as $var => $val)
        {
            Logger::log($this, "<i>POST</i><b>['{$var}' => '$val']</b>");
            $this->POST[$var] = $val;
        }

        // Read GET data...
        Logger::log($this, "Reading <b>GET</b> data...");
        foreach ($_GET as $var => $val)
        {
            Logger::log($this, "<i>GET</i><b>['{$var}' => '$val']</b>");
            $this->GET[$var] = $val;
        }
    }

}
