<?php

use \Alpha\Router\Route;
use \Alpha\Router\Router;

\Alpha\Debug\Logger::log(__FILE__, "Loading in application routes..."); #LOG



## ==================== BEGIN REQUIRED ROUTES ==================== ##

# Main Controller Routes
Router::add(
    new Route('/', ['Main' => 'Index'])
);

# 1-Page Error Controller Routes
Router::add(
    new Route('/404', ['Errors' => 'PageNotFound']) # Must be defined, or app will break if a route is not located
);



## ==================== BEGIN CUSTOM ROUTES ==================== ##
