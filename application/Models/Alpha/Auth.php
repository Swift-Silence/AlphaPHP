<?php

namespace Models\Alpha;

use \Alpha\Data\SQL\Query;
use \Alpha\Data\SQL\Table;

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
        $this->Users->Paginator->setRowsPerPage(20);
        $this->Users->Paginator->setWhere(['id' => '>300']);

        $this->log("Setting table 'sessions'...");
        $this->Sessions = $this->Table->create('sessions', $this, $this->config['tables']);

        //$r = $this->Users->select([Query::ATTR_SELECT_ALL], []);
        $r = $this->Users->selectPage(1);
        //print_r($r);
        echo "\n\n\nTOTAL PAGE COUNT:\t" . $this->Users->Paginator->getPageCount();
    }

    public function table_build_users($T)
    {
        $T->col('id', Table::COL_TYPE_BIGINT, 21, false, true, true);
        $T->col('username', Table::COL_TYPE_VARCHAR, 15);
    }

    public function table_build_sessions($T)
    {
        $T->col('id', Table::COL_TYPE_BIGINT, 21, false, true, true);
        $T->col('user_id', Table::COL_TYPE_BIGINT, 21);
        $T->col('token', Table::COL_TYPE_VARCHAR, 64);
    }

}
