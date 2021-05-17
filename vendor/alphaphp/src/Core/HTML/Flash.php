<?php 

namespace AlphaPHP\Core\HTML;

use \AlphaPHP\Core\Config;
use \AlphaPHP\Core\View;

class Flash 
{

    const TYPE_SUCCESS      = 101;
    const TYPE_NOTIFICATION = 102;
    const TYPE_ERROR        = 103;

    private $Config;

    private $messages = [];

    private $success_color;
    private $notification_color;
    private $error_color;

    private static $instance = null;

    public static function singleton()
    {
        if (static::$instance === null)
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct() 
    {
        $this->Config = Config::singleton();
    }

    public function success(string $message, $autofade = true)
    {
        $this->messages[] = (object) [
            'type' => self::TYPE_SUCCESS,
            'message' => $message,
            'autofade' => $autofade
        ];
    }

    public function notification(string $message, $autofade = true)
    {
        $this->messages[] = (object) [
            'type' => self::TYPE_NOTIFICATION,
            'message' => $message,
            'autofade' => $autofade
        ];
    }

    public function error(string $message, $autofade = true)
    {
        $this->messages[] = (object) [
            'type' => self::TYPE_ERROR,
            'message' => $message,
            'autofade' => $autofade
        ];
    }

    public function fatal(string $message)
    {
        $this->messages[] = (object) [
            'type' => self::TYPE_ERROR,
            'message' => $message,
            'autofade' => false
        ];

        $this->killAndRender();
    }

    public function renderAll()
    {
        $HTML = "<div id=\"alpha-flash-module\">\n";
        
        $HTML .= $this->renderErrors();
        $HTML .= $this->renderNotifications();
        $HTML .= $this->renderSuccesses();

        $HTML .= "</div>\n";

        return $HTML;
    }

    public function renderSuccesses()
    {
        $HTML = "";
        foreach ($this->messages as $i => $message)
        {
            if ($message->type === self::TYPE_SUCCESS) $HTML .= $this->renderBox($message);
        }

        return $HTML;
    }

    public function renderNotifications()
    {
        $HTML = "";
        foreach ($this->messages as $i => $message)
        {
            if ($message->type === self::TYPE_NOTIFICATION) $HTML .= $this->renderBox($message);
        }

        return $HTML;
    }

    public function renderErrors()
    {
        $HTML = "";
        foreach ($this->messages as $i => $message)
        {
            if ($message->type === self::TYPE_ERROR) $HTML .= $this->renderBox($message);
        }

        return $HTML;
    }

    private function renderBox(object $message)
    {
        $type = $message->type;
        $class = "alpha-flash-box";

        if ($type === self::TYPE_SUCCESS) $class .= " flash-success";
        if ($type === self::TYPE_NOTIFICATION) $class .= " flash-notification";
        if ($type === self::TYPE_ERROR) $class .= " flash-error"; 
        
        if ($message->autofade) $class .= " auto-fade";

        $HTML = "<div class=\"{$class}\">\n
            \t<p>{$message->message}</p>\n
        </div>\n";

        return $HTML;
    }

    private function killAndRender()
    {
        $View = new View(VIEWS . DS . 'Alpha/Flash/Fatal.php');
        $View->render();
        die();
    }

}