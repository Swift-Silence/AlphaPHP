<?php

use \Alpha\Router\Route;
use \Alpha\Router\Router;

\Alpha\Debug\Logger::log(__FILE__, "Loading in application routes..."); #LOG



## ==================== BEGIN REQUIRED ROUTES ==================== ##

# Home Controller Routes
Router::add(
    new Route('/',           ['Main' => 'Index']), # Cannot pass parameters to this 
    new Route('/home',       ['Main' => 'Index']),
    new Route('/home/index', ['Main' => 'Index'])
);

# 1-Page Error Controller Routes
Router::add(
    new Route('/404', ['Errors' => 'PageNotFound']) # Must be defined, or app will break if a route is not located
);



## ==================== BEGIN CUSTOM ROUTES ==================== ##
