<?php

/**
 * routes.inc.php
 *
 * Here you can define all of the routes for your application.
 */

use \Alpha\Core\Config;

use \Alpha\Router\Route;
use \Alpha\Router\Router;

// Retrieve the config object
$Config = Config::singleton();

\Alpha\Debug\Logger::log(__FILE__, "Loading in application routes..."); #LOG



## ==================== BEGIN REQUIRED ROUTES ==================== ##



# Home Controller Routes
Router::add(
    new Route('/',           ['Alpha.Main' => 'Index']), # Cannot pass parameters to this
    new Route('/home',       ['Alpha.Main' => 'Index']),
    new Route('/home/index', ['Alpha.Main' => 'Index'])
);

# 1-Page Error Controller Routes
Router::add(
    new Route($Config->get('404_PATH'), ['Errors' => 'PageNotFound']) # Must be defined, or app will break if a route is not located
);



## ==================== BEGIN CUSTOM ROUTES ==================== ##







## ==================== END CUSTOM ROUTES ==================== ##


\Alpha\Debug\Logger::log(__FILE__, "Application routes loaded.");
