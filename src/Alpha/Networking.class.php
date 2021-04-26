<?php

namespace Alpha;



class Networking
{

    public static function redirect($fw_path)
    {
        $root = rtrim(str_replace(REQUEST_ROUTE, '', REQUEST_URI), '/');

        //die($root . $fw_path);

        header('Location: /' . $root . $fw_path);
    }

}
