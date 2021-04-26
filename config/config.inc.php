<?php


## ================= DO NOT EDIT BELOW THIS LINE ========================= ##


/**
 * Define the dorectories for the project and include the autoloader
 */

// Config directory
define('CONFIG', CWD . DS . 'config');

// Application directory
define('APP', CWD . DS . 'application');

// Controllers Directory
define('CONTROLLERS', APP . DS . 'controllers');

// Models directory
define('MODELS', APP . DS . 'models');

// Views directory
define('VIEWS', APP . DS . 'views');

// Framework source directory
define('SRC', CWD . DS . 'src');

// Load the autoloader
require(CONFIG . DS . 'autoload.inc.php');


## ================= DO NOT EDIT ABOVE THIS LINE ========================= ##


// Load Config class
$Config = \Alpha\Core\Config::singleton();

## FLAGS ##
## P = Best for production
## D = Best for development

// Debug options
$Config->set('LOGGING', true); # D
$Config->set('PRINT_LOG_AFTER_EACH_RUN', true); # D

// Error Handling
$Config->set('404_PATH', '/404');


## ================= DO NOT EDIT BELOW THIS LINE ========================= ##


\Alpha\Debug\Logger::log(__FILE__, "Initial config and autoloaders loaded.");

// Require the routes.inc.php file, but catch any framework exceptions and dump the log.
try
{
    require (CONFIG . DS . 'routes.inc.php');
} catch (\Alpha\Exceptions\Exception $e)
{
    $e->dumpLog();
}
