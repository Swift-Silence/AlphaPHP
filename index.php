<?php

use \Alpha\Core\App;

/**
 * Autodetects the directory and splits up into several constants
 */

// Redefine directory separator as DS for easier use
define('DS', DIRECTORY_SEPARATOR);

// Define doucment root for easy access
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

// Define requiest URI for easy access
define('REQUEST_URI', $_SERVER['REQUEST_URI']);

// Define current working directory for easy access
define('CWD', __DIR__);

// Gets route to application relative to document root, and then gets the MVC
// request route.
$route_to_application = str_replace(DOCUMENT_ROOT, '', CWD);
define('REQUEST_ROUTE', str_replace(
    str_replace("\\", "/", $route_to_application) . '/',
    '',
    REQUEST_URI
));

/**
 * Required files to initiate framework
 */

require(CWD . DS . 'config' . DS . 'config.inc.php');

$app = new App();



\Alpha\Debug\Logger::log(__FILE__, "Application execution finished. Printing log...");
if (\Alpha\Core\Config::singleton()->get('PRINT_LOG_AFTER_EACH_RUN'))
    \Alpha\Debug\Logger::dump();

?>
