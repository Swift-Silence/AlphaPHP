<?php

namespace Alpha;

/**
 * Networking class
 *
 * Class full of useful static methods pertaining to network, requests, or connectivity.
 */

class Networking
{

    /**
     * Redirects internally within the framework.
     * @param  string $fw_path /Path/to/webpage 
     */
    public static function redirect($fw_path)
    {
        $root = rtrim(str_replace(REQUEST_ROUTE, '', REQUEST_URI), '/');

        //die(HTTP_HOST . $root . $fw_path);

        header('Location: http://' . HTTP_HOST . $root . $fw_path);
    }

}
