<?php

function fwalpha_autoload($class_name)
{
    $class_name = str_replace('\\', DS, $class_name);
    @include(SRC . DS . $class_name . '.class.php');
}

function application_autoload($class)
{
    $class = str_replace('\\', DS, $class);
    @include (APP . DS .  $class . '.php');
}

spl_autoload_register("fwalpha_autoload");
spl_autoload_register("application_autoload");
