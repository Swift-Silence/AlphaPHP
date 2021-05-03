<?php

/**
 * Autoloader for application controllers and models.
 * @param  string $class Name of the class trying to be loaded.
 */
function application_autoload($class)
{
    $class = str_replace('\\', DS, $class);
    @include (APP . DS .  $class . '.php'); # Ignore errors since we have multiple autoloaders.
}

// Register the autoloader
spl_autoload_register("application_autoload");
