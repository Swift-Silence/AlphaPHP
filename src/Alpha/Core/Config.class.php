<?php

namespace Alpha\Core;

/**
 * \Alpha\Core\Config Class
 *
 * Allows the establishment of a global config via a singleton pattern
 */

use \Alpha\Debug\Logger;

class Config {

    /**
     * All Singleton Pattern Code
     */

    /**
     * Instance of the config class
     * @var \Alpha\Core\Config
     */
    private static $instance;

    /**
     * Private constructor to restrict objectual access to within this class only.
     */
    private function __construct() {}

    /**
     * Gets the instance of this class. If it does not exist, creates and returns it.
     * @return \Alpha\Core\Config Config object.
     */
    public static function singleton() {
        if (is_object(static::$instance)) {
            return static::$instance;
        } else {
            static::$instance = new static();
            Logger::log(static::$instance, "Config object instantiated.");
            return static::$instance;
        }
    }

    /**
     * Class Code
     */

    /**
     * Holds all the configuration variables.
     * @var array
     */
    private $config;

    /**
     * Sets a config setting value.
     * @param  string             $name  Name of the setting.
     * @param  mixed              $value Value to give the setting.
     * @return \Alpha\Core\Config Config object.
     */
    public function set(string $name, $value) {
        Logger::log($this, "Config option \"<strong>{$name}</strong>\" set to \"<strong>{$value}</strong>\".");
        $this->config[$name] = $value;
        return $this;
    }

    /**
     * Gets a config setting value
     * @param  string $name Name of the setting.
     * @return mixed        Value of the setting.
     */
    public function get(string $name) {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return false;
        }
    }

}
