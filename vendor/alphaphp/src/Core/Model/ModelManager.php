<?php

namespace AlphaPHP\Core\Model;

/**
 * Model Manager class
 *
 * Helps load and maintain models. Also caches them if they need to be retrieved
 * again later.
 */

use \AlphaPHP\Debug\Logger;

class ModelManager
{

    /**
     * Mainly for logging that the model manager instantiated properly.
     */
    public function __construct(\AlphaPHP\Core\Networking\Request $R)
    {
        $this->Request = $R;

        Logger::log($this, "Model Manager object instantiated.");
    }

    /**
     * Gets, stores, and returns a loaded model class.
     * @param  string $model_name Model name using dots (.) as backslashes (\\) for namespacing.
     * @param  mixed  $params     Parameters to pass to said model.
     * @return \Alpha\Data\Model  Model object.
     */
    public function get(string $model_name, ...$params)
    {
        $model_name = str_replace('.', '\\', $model_name);

        $s_params = "";
        foreach ($params as $param)
        {
            $s_params .= "$param, ";
        }
        $s_params = substr(rtrim($s_params), 0, -1);

        Logger::log($this, "Retrieving model <b>[{$model_name}] <i>[Params: {$s_params}]</i></b>...");

        $ns_name = "Models\\{$model_name}";
        $o = new $ns_name($params);

        $o->setRequestHandler($this->Request);
    }

}
