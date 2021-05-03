<?php

namespace AlphaPHP\Core\Model\Data\SQL\Query;

interface QueryInterface
{
    // Constants for testing query type
    const ATTR_TYPE_INSERT = 100;
    const ATTR_TYPE_SELECT = 101;
    const ATTR_TYPE_UPDATE = 102;
    const ATTR_TYPE_DELETE = 103;

    // Special Select all constant
    const ATTR_SELECT_ALL = "*";
}
