<?php 

namespace Controllers;

class Account extends \AlphaPHP\Controller 
{

    public function __construct()
    {
        parent::__construct();

        $this->Auth = $this->Model->get('Alpha.Auth');
    }

    public function Register($params = [])
    {

        

        $this->setView();
        $this->View->render();

    }

}