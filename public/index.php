<?php 

require(dirname(__DIR__) . '/vendor/autoload.php');

use \AlphaPHP\Core\App;
use \AlphaPHP\Core\HTML\Flash;

$Flash = Flash::singleton();

// Begin PHP sessions 
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
define('CWD', dirname(__DIR__));

//die(REQUEST_URI);



// Establish HTTP_HOST for easy access. Falls back to SERVER_NAME if HTTP_HOST index is not set.
$http_host = (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
define('HTTP_HOST', $http_host);

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define('URL', $protocol . HTTP_HOST);


/**
 * Required files to initiate framework
 */

require(CWD . DS . 'config' . DS . 'config.inc.php');
//echo "<pre>" . print_r(error_get_last(), true);
//phdpinfo();

/**
 * Run the application
 */

try {
    $app = new App();
} catch (\AlphaPHP\Exceptions\Exception $e) {
    $e->fatal();
} catch (\Throwable $e) {
    $Flash->fatal("<b>[{$e->getFile()}:{$e->getLine()}]</b> threw " . get_class($e) . ": [{$e->getCode()}]: {$e->getMessage()}<br/><br/>Stack Trace: {$e->getTraceAsString()}");
}

// Debug logger code - Only runs when logging is enabled and only dumps if configuration
// is set for it.
\AlphaPHP\Debug\Logger::log(__FILE__, "Application execution finished. Printing log...");
\AlphaPHP\Debug\Logger::log(__FILE__, "Executed " . \AlphaPHP\Core\Model\Data\DB::$executed_queries . " SQL queries.");

if (\AlphaPHP\Core\Config::singleton()->get('PRINT_LOG_AFTER_EACH_RUN'))
    \AlphaPHP\Debug\Logger::dump();
    