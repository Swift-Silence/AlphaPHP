<?php

namespace Models\Alpha;

use \Alpha\Data\SQL\Query;
use \Alpha\Data\SQL\Table\Type;

class Auth extends \Alpha\Data\Model
{

    private $config = [
        'tables' => [
            'table_prefix' => 'auth_'
        ]
    ];

    private $Users;
    private $Sessions;

    public function __construct($params)
    {
        parent::__construct();

        $this->log("Setting table 'users'...");
        $this->Users = $this->Table->create('users', $this, $this->config['tables']);

        $this->log("Setting table 'sessions'...");
        $this->Sessions = $this->Table->create('sessions', $this, $this->config['tables']);

        $this->log("Selecting some data...");
        $this->Users->select([Query::ATTR_SELECT_ALL], [], ['id' => 'ASC'], '2,1');
    }

    public function table_build_users($T)
    {
        $T->col('id', Type::BIGINT, 21, false, true, true);
        $T->col('username', Type::VARCHAR, 15);
    }

    public function table_build_sessions($T)
    {
        $T->col('id', Type::BIGINT, 21, false, true, true);
        $T->col('user_id', Type::BIGINT, 21);
        $T->col('token', Type::VARCHAR, 64);
    }

    public function users_table_before_username($val)
    {
        return "LOL_$val";
    }

    public function users_table_after_id($val)
    {
        return "00" . $val;
    }

    public function users_table_after_username($val)
    {
        return "{$val}_LOL";
    }

}
