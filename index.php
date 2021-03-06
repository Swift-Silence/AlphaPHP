<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body style="background: #000; color: #fff;">

    </body>
</html>

<?php

use \Alpha\Core\App;

session_start();
ob_start();

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
$req_route = str_replace(
    str_replace("\\", "/", $route_to_application) . '/',
    '',
    REQUEST_URI
);
$getpos = strpos($req_route, '?');
$req_route = ($getpos) ? substr($req_route, 0, $getpos) : $req_route;
define('REQUEST_ROUTE', $req_route);
//die(REQUEST_ROUTE);

// Establish HTTP_HOST for easy access. Falls back to SERVER_NAME if HTTP_HOST index is not set.
$http_host = (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
define('HTTP_HOST', $http_host);

/**
 * Required files to initiate framework
 */

require(CWD . DS . 'config' . DS . 'config.inc.php');
echo "<pre>" . print_r(error_get_last(), true);
//phdpinfo();

/**
 * Run the application
 */

$app = new App();

// Debug logger code - Only runs when logging is enabled and only dumps if configuration
// is set for it.
\Alpha\Debug\Logger::log(__FILE__, "Application execution finished. Printing log...");
\Alpha\Debug\Logger::log(__FILE__, "Executed " . \Alpha\Data\SQL\DB::$executed_queries . " SQL queries.");

if (\Alpha\Core\Config::singleton()->get('PRINT_LOG_AFTER_EACH_RUN'))
    \Alpha\Debug\Logger::dump();

?>
