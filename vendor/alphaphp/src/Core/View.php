<?php 

namespace AlphaPHP\Core;

use \AlphaPHP\Debug\Logger;

class View 
{

    private $path;

    private $vars = [];

    public function __construct(string $path, array $vars)
    {
        $this->path = $path;
        $this->vars = $vars;

        $this->log('View manager object instantiated.');

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
        include($this->path);
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