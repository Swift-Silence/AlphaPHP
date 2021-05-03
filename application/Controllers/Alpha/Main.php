<?php

namespace Controllers\Alpha;

class Main extends \AlphaPHP\Controller
{

    public function __construct()
    {
        parent::__construct();

        #
    }

    public function Index($params = [])
    {
        //$this->Model->get('Alpha.Auth');
        //$this->log('test');

        $this->var('username', 'TAustin2017');
        $this->view();
    }

}
