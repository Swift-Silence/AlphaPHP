<?php

/**
 * \Alpha\Core\Logger
 *
 * @author Tyler A. Smith
 * @version 1.0.0
 */

namespace Alpha\Debug;

use \Alpha\Core\Config;

class Logger {

    /**
     * Hold the entirety of the logged data.
     * @var array
     */
    private static $log = [];

    /**
     * Adds to the log
     *
     * @param  object $class   The class calling the logger
     * @param  string $message Message being logged
     */
    public static function log($class, $message)
    {
        if (!Config::singleton()->get('LOGGING')) return;

        if (empty(static::$log)) {
            static::$log[] = "<strong>" . __CLASS__ . "</strong>: Logger enabled.\n";
        }

        $class = is_object($class) ? get_class($class) : basename($class);
        static::$log[] = "<strong>{$class}</strong>: {$message}\n";
    }

    /**
     * Adds an empty line to the log
     * @param  mixed   $class Object or string that gets logged with the line. Optional.
     * @param  integer $n     How many empty lines to add
     */
    public static function _($class = ".", $n = 1)
    {
        if (!Config::singleton()->get('LOGGING')) return;

        $class = is_object($class) ? get_class($class) : basename($class);
        for ($i = 0; $i < $n; $i++)
        {
            static::$log[] = "<strong>{$class}</strong>: .\n";
        }
    }

    /**
     * Dumps the entirety of the logged contents. Passing 'true' will kill the
     * application.
     *
     * @param  boolean $die Whether the application should die after the dump.
     */
    public static function dump($die = false) {
        echo "<pre>";

        foreach (static::$log as $log) {
            echo $log;
        }

        echo "</pre>";
        if ($die) die();
    }

}
