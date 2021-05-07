<?php 

namespace AlphaPHP\Core\HTML\Helpers;

use \AlphaPHP\Debug\Logger;

/**
 * Form helper that helps the frontend developer quickly prototype a form that can be submitted to 
 * the backend.
 */
class FormHelper 
{

    // FORM INPUT TYPE CONSTANTS FOR LOGICAL USE
    const TYPE_BUTTON           = 201;
    const TYPE_CHECKBOX         = 202;
    const TYPE_COLOR            = 203;
    const TYPE_DATE             = 204;
    const TYPE_DATETIME_LOCAL   = 205;
    const TYPE_EMAIL            = 206;
    const TYPE_FILE             = 207;
    const TYPE_HIDDEN           = 208;
    const TYPE_IMAGE            = 209;
    const TYPE_MONTH            = 210;
    const TYPE_NUMBER           = 211;
    const TYPE_PASSWORD         = 212;
    const TYPE_RADIO            = 213;
    const TYPE_RANGE            = 214;
    const TYPE_RESET            = 215;
    const TYPE_SEARCH           = 216;
    const TYPE_SELECT           = 217; # Not a valid HTML type attribute, but added to represent <select> type in PHP code.
    const TYPE_SUBMIT           = 218;
    const TYPE_TEL              = 219;
    const TYPE_TEXT             = 220;
    const TYPE_TIME             = 221;
    const TYPE_URL              = 222;
    const TYPE_WEEK             = 223;
    const TYPE_EDITOR           = 224; # Not a valid HTML type attribute, but added to represent <textarea> type in PHP code.



    /**
     * Holds the current type of input field for logical use 
     *
     * @var int 
     */
    private $type = null;

    /**
     * Mainly for logging purposes.
     */
    public function __construct()
    {
        Logger::log($this, "Form Helper loaded.");
    }

    /**
     * Begins a new HTML form field
     *
     * @param string $name
     * @param string $action
     * @param string $type
     * @param boolean $is_file_upload
     * @param string $ID
     * @param array $classes
     * @return string
     */
    public function start(string $name, string $action, string $type = "GET", $is_file_upload = false, string $ID = null, array $classes = null)
    {
        $type = strtoupper($type);
        $HTML = "<form name=\"{$name}\" action=\"{$action}\" method=\"{$type}\"";

        if ($is_file_upload) $HTML .= " enctype=\"multipart/form-data\"";
        if (!empty($ID)) $HTML .= " id=\"{$ID}\"";
        
        if (!empty($classes) && is_array($classes))
        {
            $HTML .= " class=\"";
            foreach ($classes as $class)
            {
                $HTML .= "$class ";
            }
            $HTML = rtrim($HTML) . "\"";
        }

        $HTML .= ">\n";

        return $HTML;
    }

    /**
     * Adds a button to the DOM
     *
     * @param string $name
     * @param string $value
     * @param array $attr
     * @return string
     */
    public function button($name, $value, $attr = [])
    {
        $this->type = self::TYPE_BUTTON;

        $HTML = "<input type=\"button\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a checkbox to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function checkbox($name, $value, $attr = [])
    {
        $this->type = self::TYPE_CHECKBOX;

        $HTML = "<input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a color picker to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function color($name, $value = "", $attr = []) # Firefox shows Windows default color picker 5/6/21
    {
        $this->type = self::TYPE_COLOR;

        $HTML = "<input type=\"color\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a date picker to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function date($name, $value = "", $attr = [])
    {
        $this->type = self::TYPE_DATE;

        $HTML = "<input type=\"date\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a local datetime picker to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function datetime_local($name, $value = "", $attr = []) # Unsupported by Firefox 5/6/21
    {
        $this->type = self::TYPE_DATETIME_LOCAL;

        $HTML = "<input type=\"datetime-local\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds an email field the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function email($name, $value = "", $attr = [])
    {
        $this->type = self::TYPE_EMAIL;

        $HTML = "<input type=\"email\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a file uploader to the DOM
     *
     * @param string $name
     * @param array $attr
     * @return string
     */
    public function file($name, $attr = [])
    {
        $this->type = self::TYPE_FILE;

        $HTML = "<input type=\"file\" name=\"{$name}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a hidden input to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function hidden($name, $value, $attr = [])
    {
        $this->type = self::TYPE_HIDDEN;

        $HTML = "<input type=\"hidden\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds image submit button to the DOM
     *
     * @param string $name
     * @param array $attr
     * @return string
     */
    public function image($name, $attr = [])
    {
        $this->type = self::TYPE_IMAGE;

        $HTML = "<input type=\"image\" name=\"{$name}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a month picker to the DOM 
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function month($name, $value = "", $attr = []) # Unsupported by Firefox 5/7/21
    {
        $this->type = self::TYPE_MONTH;

        $HTML = "<input type=\"month\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a number field to the DOM
     *
     * @param string $name
     * @param int $value
     * @param array $attr
     * @return string
     */
    public function number($name, $value = "", $attr = []) 
    {
        $this->type = self::TYPE_NUMBER;

        $HTML = "<input type=\"number\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a password field to the DOM
     *
     * @param string $name
     * @param array $attr
     * @return string
     */
    public function password($name, $attr = []) 
    {
        $this->type = self::TYPE_PASSWORD;

        $HTML = "<input type=\"password\" name=\"{$name}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a radio button to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function radio($name, $value, $attr = [])
    {
        $this->type = self::TYPE_RADIO;

        $HTML = "<input type=\"radio\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a range slider to the DOM
     *
     * @param string $name
     * @param integer $min
     * @param integer $max
     * @param int $value
     * @param array $attr
     * @return string
     */
    public function range($name, $min = 0, $max = 100, $value = "", $attr = [])
    {
        $this->type = self::TYPE_RANGE;

        $HTML = "<input type=\"range\" name=\"{$name}\" value=\"{$value}\" min=\"{$min}\" max=\"{$max}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a form reset button to the DOM
     *
     * @param array $attr
     * @return string
     */
    public function reset($attr = [])
    {
        $this->type = self::TYPE_RESET;

        $HTML = "<input type=\"reset\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a search field to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function search($name, $value = "", $attr = [])
    {
        $this->type = self::TYPE_SEARCH;

        $HTML = "<input type=\"search\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a select (drop down) menu to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @param array $attr
     * @return string
     */
    public function select($name, $value, array $options, array $attr = [])
    {
        $this->type = self::TYPE_SELECT;

        $HTML = "<select name=\"{$name}\" " . $this->getAttr($attr) . ">\n";
        
        foreach ($options as $val => $label)
        {
            $HTML .= "\t<option value=\"{$val}\"";
            
            if ($val == $value) $HTML .= " selected=\"selected\"";

            $HTML .= ">{$label}</option>\n";
        }

        $HTML .= "</select>\n";

        return $HTML;
    }

    /**
     * Adds a submit button to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function submit($name, $value, $attr = [])
    {
        $this->type = self::TYPE_SUBMIT;

        $HTML = "<input type=\"submit\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a telephone number field to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function tel($name, $value = "", $attr = [])
    {
        $this->type = self::TYPE_TEL;

        $HTML = "<input type=\"tel\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a text field to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function text($name, $value = "", $attr = [])
    {
        $this->type = self::TYPE_TEXT;

        $HTML = "<input type=\"text\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a text area to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function textarea($name, $value = "", $attr = [])
    {
        $this->type = self::TYPE_TEXT;

        $HTML = "<textarea name=\"{$name}\" " . $this->getAttr($attr) . ">{$value}</textarea>";
        return $HTML;
    }

    /**
     * Adds a time picker to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function time($name, $value = "", $attr = [])
    {
        $this->type = self::TYPE_TIME;

        $HTML = "<input type=\"time\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a URL field to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function url($name, $value = "", $attr = [])
    {
        $this->type = self::TYPE_URL;

        $HTML = "<input type=\"url\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds a week picker to the DOM
     *
     * @param string $name
     * @param mixed $value
     * @param array $attr
     * @return string
     */
    public function week($name, $value = "", $attr = [])
    {
        $this->type = self::TYPE_WEEK;

        $HTML = "<input type=\"week\" name=\"{$name}\" value=\"{$value}\" " . $this->endTag($attr);
        return $HTML;
    }

    /**
     * Adds the closing form tag to the DOM
     *
     * @return string
     */
    public function end()
    {
        return "</form>\n";
    }

    /**
     * Easy way to end all input tags without having to repeat code 
     *
     * @param array $attr
     * @return string
     */
    private function endTag($attr)
    {
        $HTML = $this->getAttr($attr) . " />\n";
        return $HTML;
    }

    /**
     * Parses all attributes and returns in HTML notation 
     *
     * @param array $attr
     * @return string
     */
    private function getAttr($attr)
    {
        $HTML = "";

        foreach ($attr as $attr_name => $value)
        {
            $HTML .= "{$attr_name}=\"{$value}\" ";
        }

        return rtrim($HTML);
    }

}