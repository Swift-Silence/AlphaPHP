<?php

namespace Models\Alpha;



class Auth extends \Alpha\Data\Model
{

    protected $params;

    public function __construct($params)
    {
        $this->params = $params;

        parent::__construct(true);
    }

}
