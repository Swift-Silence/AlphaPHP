<?php

function fwalpha_autoload($class_name)
{
    $class_name = str_replace('\\', DS, $class_name);
    include(SRC . DS . $class_name . '.class.php');
}

spl_autoload_register("fwalpha_autoload");
