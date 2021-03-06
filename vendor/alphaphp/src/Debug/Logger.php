<?php

/**
 * \Alpha\Core\Logger
 *
 * @author Tyler A. Smith
 * @version 1.0.0
 */

namespace AlphaPHP\Debug;

use \AlphaPHP\Core\Config;

class Logger {

    /**
     * Hold the entirety of the logged data.
     * @var array
     */
    private static $log = [];

    /**
     * List of framework classes to exclude if Config setting SHOW_FRAMEWORK_LOGS is set to false.
     * @var array
     */
    private static $framework_classes = [
        'Alpha\\Debug\\Logger',
        'Alpha\\Core\\Config',
        'Alpha\\Router\\Route',
        'config.inc.php',
        'routes.inc.php',
        'Alpha\\Core\\App',
        'Alpha\\Router\\Router',
        //'Alpha\\Core\\Controller',
        'Alpha\\Networking\\Request',
        'Alpha\\Networking\\Request\\CookieHandler',
        'Alpha\\Networking\\Request\\SessionHandler',
        'Alpha\\Core\\ModelManager',
        'Alpha\\Data\\SQL\\DB',
        'Alpha\\Data\\SQL\\TableManager',
        'Alpha\\Data\\SQL\\Table',
        'Alpha\\Data\\SQL\\Table\\TableBuilder',
        'Alpha\\Data\\SQL\\QueryBuilder',
        'Alpha\\Data\\SQL\\Query'
    ];

    /**
     * Adds to the log
     *
     * @param  object $class   The class calling the logger
     * @param  string $message Message being logged
     */
    public static function log($class, $message)
    {
        if (!Config::singleton()->get('LOGGING')) return;

        $class = is_object($class) ? get_class($class) : basename($class);

        if (in_array($class, static::$framework_classes) && !Config::singleton()->get('SHOW_FRAMEWORK_LOGS')) return; // Cancel if log comes from a framework class.

        if (empty(static::$log)) {
            static::$log[] = "<strong>" . static::getTimestamp() . " " . __CLASS__ . "</strong>: Logger enabled.\n"; // Adds this to the log if there has been no prior entries.
        }

        static::$log[] = "<strong>" . static::getTimestamp() . " {$class}</strong>: {$message}\n";
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
            static::$log[] = "<strong>" . static::getTimestamp() . " {$class}</strong>: ...\n";
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

    /**
     * Returns the current timestamp
     * @return string Formatted date string
     */
    private static function getTimestamp()
    {
        $format = "[F j, Y][G:i:s." . static::milliseconds(true) . "]";
        return date($format, time());
    }

    /**
     * Returns milliseconds since unix epoch
     * @param  boolean $mod Whether to perform ms % 1000 to get ms since last second.
     * @return int          Milliseconds
     */
    private static function milliseconds($mod = false)
    {
        $mt = explode(' ', microtime());
        $val = ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));

        $modval = $val % 1000;
        if ($modval < 100) $modval = "0" . $modval;
        else if ($modval < 10) $modval = "00" . $modval;
        else if ($modval == 0) $modval = "000";

        return ($mod) ? $modval : $val;
    }

}
