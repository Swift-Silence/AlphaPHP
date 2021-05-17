<?php 

namespace AlphaPHP\Core\HTML\Helpers;

/**
 * Simple Helper that contains function pertaining to JS client-side code.
 */
class JSHelper 
{

    /**
     * Holds whether jQuery has already been included in the DOM.
     *
     * @var boolean
     */
    private $jQuery_loaded = false;

    /**
     * Constructor
     */
    public function __construct() {}

    /**
     * jQuery loader
     *
     * @return string jQuery CDN 
     */
    public function jQuery()
    {
        if ($this->jQuery_loaded) return "\n";

        $this->jQuery_loaded = true;
        return "<script src=\"https://code.jquery.com/jquery-3.6.0.min.js\" integrity=\"sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=\" crossorigin=\"anonymous\"></script>\n";
    }

}