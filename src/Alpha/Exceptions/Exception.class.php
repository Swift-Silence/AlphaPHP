<?php

namespace Alpha\Exceptions;



use \Alpha\Debug\Logger;

class Exception extends \Exception
{

    public function __construct($message, $code = 0, $previous = null)
    {
        $file = $this->getFile();
        $line = $this->getLine();

        for ($i = 0; $i < 5; $i++)
        {
            Logger::log(':', ':');
        }
        Logger::log($this, $message);
        Logger::log($this, "[File: <Strong>{$file}</strong>]\t[Line: <strong>{$line}</strong>]");

        parent::__construct($message, $code, $previous);
    }

    public function dumpLog()
    {
        Logger::dump(true);
    }

}
