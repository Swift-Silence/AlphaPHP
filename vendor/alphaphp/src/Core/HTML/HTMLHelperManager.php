<?php 

namespace AlphaPHP\Core\HTML;

use \AlphaPHP\Core\HTML\Helpers\FormHelper;
use \AlphaPHP\Core\HTML\Helpers\JSHelper;

use \AlphaPHP\Debug\Logger;

class HTMLHelperManager 
{

    public $Form;
    public $JS;

    public function __construct()
    {
        Logger::log($this, "HTML Helper Manager instantiated.");

        $this->Form = new FormHelper();
        $this->JS   = new JSHelper();
    }



    // Basic HTML helper functions that don't require secondary classes

    /**
     * Adds a new link to the DOM
     *
     * @param string $text
     * @param string $url
     * @param boolean $open_new_tab
     * @param array $attr
     * @return string
     */
    public function link($text, $url, $open_new_tab = false, $attr = [])
    {
        if (substr($url, 0, 1) == "/") // Checks of the first character is a / 
        {
            $url = URL . $url; // Point to internal page directly to avoid issues
        }

        $HTML = "<a href=\"{$url}\""; // Begin anchor tag

        if ($open_new_tab) $HTML .= " target=\"_blank\""; // add new tab code if desired to be opened in new tab

        foreach ($attr as $name => $val) // Sift through and add extra attributes
        {
            $HTML .= " {$name}=\"{$val}\"";
        }

        $HTML .= ">{$text}</a>\n"; // Add link text and closing anchor tag

        return $HTML;
    }

}