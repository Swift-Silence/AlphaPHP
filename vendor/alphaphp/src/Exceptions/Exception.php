<?php

namespace AlphaPHP\Exceptions;

/**
 * Core Exception class
 *
 * All AlphaPHP exceptions extend this, and allows the log to be dumped via a
 * caught object. Also automatically logs the error message, including the
 * filename and line the error was thrown.
 */

use \AlphaPHP\Core\HTML\Flash;
use \AlphaPHP\Debug\Logger;

class Exception extends \Exception
{

    protected $Flash;

    /**
     * Log error and pass to parent constructor.
     * @param string  $message  Error message
     * @param integer $code     Error code
     * @param \Exception        Previous exception
     */
    public function __construct($message, $code = 0, $previous = null)
    {
        $this->Flash = Flash::singleton();
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

    /**
     * Dumps the whole log from \Alpha\Debug\Logger
     */
    public function dumpLog() // Deprecated
    {
        Logger::dump(true);
    }

    /**
     * Alias for dumpLog()
     */
    public function dump()
    {
        $this->dumpLog();
    }

    public function fatal()
    {
        $this->Flash->fatal("<b>[{$this->getFile()}:{$this->getLine()}]</b> threw " . get_class($this) . ": [{$this->getCode()}]: {$this->getMessage()}<br/><br/>Stack Trace: {$this->getTraceAsString()}");
    }

    public function flash()
    {
        $this->Flash->error("<b>[{$this->getFile()}:{$this->getLine()}]</b> threw " . get_class($this) . ": [{$this->getCode()}]: {$this->getMessage()}<br/><br/>Stack Trace: {$this->getTraceAsString()}");
    }

}
