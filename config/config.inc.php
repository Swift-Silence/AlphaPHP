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
define('CONTROLLERS', APP . DS . 'Controllers');

// Models directory
define('MODELS', APP . DS . 'Models');

// Resources directory 
define('RESOURCES', CWD . DS . 'resources');

// Views directory
define('VIEWS', APP . DS . 'Views');

// Load the autoloader
require(CONFIG . DS . 'autoload.inc.php');


## ================= DO NOT EDIT ABOVE THIS LINE ========================= ##


// Load Config class
$Config = \AlphaPHP\Core\Config::singleton();

## FLAGS ##
## P = Best for production
## D = Best for development

// Debug options
$Config->set('LOGGING', true); # D
$Config->set('SHOW_FRAMEWORK_LOGS', true); # D
$Config->set('PRINT_LOG_AFTER_EACH_RUN', false); # D

// Database settings
$Config->set('DB_HOST',     '127.0.0.1');
$Config->set('DB_USER',     'root');
$Config->set('DB_PASSWORD', 'tylerboo22');
$Config->set('DB_DB',       'alphaphp');

// Error Handling
$Config->set('404_PATH', '/404');

// File Handler settings 
$Config->set('FILES/ALLOWED_EXTENSIONS', ['jpg', 'png', 'jpeg', 'gif']);
$Config->set('FILES/DEFAULT_UPLOAD_DIR', RESOURCES . DS . 'uploaded');
$Config->set('FILES/DEFAULT_SIZE_LIMIT', '5MB');

// Flash Error/Notification/Success settings 
$Config->set('FLASH/SUCCESS_COLOR', "#00FF00"); // Green
$Config->set('FLASH/NOTIFICATION_COLOR', "#0000FF"); // Blue
$Config->set('FLASH/ERROR_COLOR', "#FF0000"); // Red


## ================= DO NOT EDIT BELOW THIS LINE ========================= ##


\AlphaPHP\Debug\Logger::log(__FILE__, "Initial config and autoloaders loaded.");

// Require the routes.inc.php file, but catch any framework exceptions and dump the log.
try
{
    require (CONFIG . DS . 'routes.inc.php');
} 
catch (\AlphaPHP\Exceptions\Exception $e)
{
    $e->dumpLog();
}
