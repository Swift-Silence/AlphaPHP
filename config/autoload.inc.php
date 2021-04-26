<?php

/**
 * Autoloader for framework class files
 * @param  string $class_name Name of the class trying to be loaded.
 */
function fwalpha_autoload($class_name)
{
    $class_name = str_replace('\\', DS, $class_name);
    @include(SRC . DS . $class_name . '.class.php'); # Ignore errors since we have multiple autoloaders.
}

/**
 * Autoloader for application controllers and models.
 * @param  string $class Name of the class trying to be loaded.
 */
function application_autoload($class)
{
    $class = str_replace('\\', DS, $class);
    @include (APP . DS .  $class . '.php'); # Ignore errors since we have multiple autoloaders.
}

// Register the autoloaders 
spl_autoload_register("fwalpha_autoload");
spl_autoload_register("application_autoload");
