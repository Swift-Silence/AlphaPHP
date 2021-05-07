<?php 

namespace AlphaPHP\Core\HTML;

use \AlphaPHP\Core\HTML\Helpers\FormHelper;

use \AlphaPHP\Debug\Logger;

class HTMLHelperManager 
{

    public function __construct()
    {
        Logger::log($this, "HTML Helper Manager instantiated.");

        $this->Form = new FormHelper();
    }

}