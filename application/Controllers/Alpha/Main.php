<?php

namespace Controllers\Alpha;

class Main extends \AlphaPHP\Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->Model->get('Alpha.Auth');
    }

    public function Index($params = [])
    {
        if ($this->Request->post('yay'))
        {
            $this->Request->File->upload('myfile');
        }
        //echo $this->Request->File->dump();
        echo $this->Request->File->getSize('myfile');

        $this->setView();
        $this->View->render();
    }

}
