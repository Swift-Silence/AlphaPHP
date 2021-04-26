<?php

namespace Alpha\Core;



use \Alpha\Debug\Logger;

class ModelManager
{

    public function __construct()
    {
        Logger::log($this, "Model Manager object instantiated.");
    }

    public function get($model_name, ...$params)
    {
        Logger::log($this, "Retrieving model <b>{$model_name}</b>...\n\nParams:\n" . print_r($params, true));

        $model_name = str_replace('.', '\\', $model_name);
        $ns_name = "Models\\{$model_name}";
        return new $ns_name($params);
    }

}
