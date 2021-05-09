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
        $this->var('color', $this->Request->post('color'));
        $this->var('date', $this->Request->post('date'));
        $this->var('datetimelocal', $this->Request->post('datetimelocal'));
        $this->var('month', $this->Request->post('month'));
        $this->var('mynum', $this->Request->post('mynum'));
        $this->var('somn', $this->Request->post('somn'));
        $this->var('search', $this->Request->post('search'));
        $this->var('tel', $this->Request->post('tel'));
        $this->var('time', $this->Request->post('time'));
        $this->var('url', $this->Request->post('url'));
        $this->var('week', $this->Request->post('week'));
        $this->var('mysel', $this->Request->post('mysel'));

        $this->setView();
        $this->View->render();
    }

}
