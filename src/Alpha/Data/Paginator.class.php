<?php

namespace Alpha\Data;

use \Alpha\Data\SQL\Query;
use \Alpha\Debug\Logger;

/**
 * Allows for data to be paginated. Can be used via a table's selectPage() method.
 *
 * Can be configured via Table::$Paginator->set_per_page();
 */
class Paginator
{

    /**
     * Holds paginator configuration.
     * @var array
     */
    private $config = [
        'rows_per_page' => 10
    ];

    private $order_by = false;

    private $where = [];

    /**
     * Logs instantiation and sets config and table properties.
     * @param \Alpha\Data\SQL\Table  $Table  Table object
     * @param array                  $config Configuration
     */
    public function __construct($Table, $config)
    {
        $this->Table = $Table;
        $this->config = array_merge($this->config, $config);

        $this->log("Data paginator instantiated.");
        $this->getPageCount();
    }

    /**
     * Sets the rows_per_page configuration value
     * @param int $rows_per_page Number of rows per page
     */
    public function setRowsPerPage(int $rows_per_page)
    {
        $this->config['rows_per_page'] = $rows_per_page;
        $this->log("Config option <b>rows_per_page</b> set to <b>{$rows_per_page}</b>.");
        $this->getPageCount();
    }

    public function setOrderBy(array $order_by)
    {
        $this->order_by = $order_by;
    }

    public function setWhere(array $where)
    {
        $this->where = $where;
        $this->getPageCount();
    }

    public function getPage(int $page_number)
    {
        if ($page_number > 0) $page_number -= 1;

        $offset = $page_number * $this->config['rows_per_page'];

        $selectors  = [Query::ATTR_SELECT_ALL];
        $where      = $this->where;
        $order_by   = $this->order_by;
        $limit      = "{$offset},{$this->config['rows_per_page']}";

        return $this->Table->select($selectors, $where, $order_by, $limit);
    }

    public function getPageCount()
    {
        $count = ceil($this->Table->countRows($this->where) / $this->config['rows_per_page']);
        $this->log("There are <b>{$count}</b> pages of data in the table. (<i>{$this->config['rows_per_page']} entries per page.</i>)");
        return $count;
    }

    private function log($message)
    {
        Logger::log($this, "<b>[{$this->Table->name}]</b> $message");
    }

}
