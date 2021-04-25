<?php

namespace Alpha\Core;

use \Alpha\Debug\Logger;

class Config {

    /**
     * All Singleton Pattern Code
     */

    private static $instance;

    private function __construct() {}

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

    private $config;

    public function set($name, $value) {
        Logger::log($this, "Config option \"<strong>{$name}</strong>\" set to \"<strong>{$value}</strong>\".");
        $this->config[$name] = $value;
        return $this;
    }

    public function get($name) {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return false;
        }
    }

}
