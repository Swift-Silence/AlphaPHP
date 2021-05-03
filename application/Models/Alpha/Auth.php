<?php

namespace Models\Alpha;

use \AlphaPHP\Core\Model\Data\SQL\Query;
use \AlphaPHP\Core\Model\Data\SQL\Table;

class Auth extends \AlphaPHP\Model
{

    private $config = [
        'tables' => [
            'table_prefix' => 'auth_'
        ]
    ];

    public function __construct($params)
    {
        parent::__construct();

        $this->TUsers = $this->Table->create('users', $this, $this->config['tables']);
        $this->TSessions = $this->Table->create('sessions', $this, $this->config['tables']);
        $this->TPermissions = $this->Table->create('perm_groups', $this, $this->config['tables']);
    }

    public function table_build_users($T)
    {
        $T->col('id',                       Table::COL_TYPE_INT,        21, false, true, true);
        $T->col('email',                    Table::COL_TYPE_VARCHAR,    64, false);
        $T->col('password',                 Table::COL_TYPE_VARCHAR,    64, false);
        $T->col('salt',                     Table::COL_TYPE_VARCHAR,    64, false);
        $T->col('permission_group',         Table::COL_TYPE_VARCHAR,    15, false);
        $T->col('registration_date_unix',   Table::COL_TYPE_BIGINT,     21, false);
        $T->col('last_seen_unix',           Table::COL_TYPE_BIGINT,     21, false);
    }

    public function table_build_sessions($T)
    {
        $T->col('id',           Table::COL_TYPE_BIGINT,     21, false, true, true);
        $T->col('user_id',      Table::COL_TYPE_INT,        21, false);
        $T->col('token',        Table::COL_TYPE_VARCHAR,    64, false);
        $T->col('ip_address',   Table::COL_TYPE_VARCHAR,    15, false);
        $T->col('expiry',       Table::COL_TYPE_BIGINT,     21, false);
    }

    public function table_build_perm_groups($T)
    {
        $T->col('id',               Table::COL_TYPE_VARCHAR, 15,    false, true);
        $T->col('perm_nodes_json',  Table::COL_TYPE_TEXT,    false, true);
    }

}
