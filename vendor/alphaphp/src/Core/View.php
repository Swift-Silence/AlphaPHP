<?php 

namespace AlphaPHP\Core;

use \AlphaPHP\Core\HTML\Flash;
use \AlphaPHP\Debug\Logger;

class View 
{

    private $Flash;

    private $HTML;

    private $path;

    private $vars = [];

    public function __construct(string $path, array $vars = [], $HTML = null)
    {
        $this->path = $path;
        $this->vars = $vars;
        $this->HTML = $HTML;
        $this->Flash = Flash::singleton();

        $this->log('View object instantiated.');

        $this->log('Checking if the view file exists...');
        if (!file_exists($path))
        {
            throw new \AlphaPHP\Exceptions\System\FileNotFoundException("View <b>{$this->path}</b> not found.");
        }

        $this->log('Found view source file!');
        $this->log('Establishing variables as properties of class...');
        $this->rebaseVars();
    }

    public function render()
    {
        $this->log("Rendering view...");
        include($this->path);
    }

    private function _($path)
    {
        $path = str_replace(['\\', '.'], DS, $path);
        $path = VIEWS . DS . $path . ".php";
        
        $this->log("Attempting to load in view dependency <b>{$path}</b>...");
        $V = new View($path, $this->vars, $this->HTML);
        $V->render();
    }

    private function rebaseVars()
    {
        foreach ($this->vars as $name => $value)
        {
            $this->$name = $value;
            $this->log("public <b>View</b>::<i>\${$name}</i> = <b>$value</b>;");
        }
    }



    private function log(string $message)
    {
        Logger::log($this, "<b>[{$this->path}]</b> $message");
    }

}