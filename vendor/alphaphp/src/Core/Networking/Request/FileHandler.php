<?php

namespace AlphaPHP\Core\Networking\Request;

use \AlphaPHP\Core\Config;

/**
 * Handles file uploads and allows for easy configuration of size and extension limitations.
 */
class FileHandler 
{

    /**
     * Array of allowed extensions
     *
     * @var array
     */
    private $allowed_extensions = [];

    /**
     * Config object 
     *
     * @var \AlphaPHP\Core\Config
     */
    private $Config;

    /**
     * Holds the $_FILES superglobal 
     *
     * @var array
     */
    private $files = [];

    /**
     * The size limit in bytes 
     *
     * @var int
     */
    private $size_limit;

    /**
     * Directory to move all uploaded files to.
     *
     * @var string
     */
    private $upload_dir;

    /**
     * Establish defaults
     */
    public function __construct()
    {
        $this->files  = $_FILES;
        $this->Config = Config::singleton(); 

        $this->allowed_extensions   = array_merge(
            $this->Config->get('FILES/ALLOWED_EXTENSIONS'), 
            $this->allowed_extensions
        );

        $this->limitSize($this->Config->get('FILES/DEFAULT_SIZE_LIMIT'));
        $this->setUploadDir($this->Config->get('FILES/DEFAULT_UPLOAD_DIR'));
    }

    /**
     * Allow extensions to be uploaded.
     *
     * @param string ...$extensions
     * @return void
     */
    public function allowExt(...$extensions)
    {
        $this->allowed_extensions = array_merge($extensions, $this->allowed_extensions);
    }

    /**
     * Dumps all uploaded file info. For debug purposes only.
     *
     * @return void
     */
    public function dump()
    {
        echo "<pre>";
        var_dump($this->files);
        echo "</pre>";
    }

    /**
     * Returns the size of a file upload 
     *
     * @param string $name
     * @param boolean $bytes
     * @return void
     */
    public function getSize($name, $bytes = false)
    {
        if (isset($this->files[$name]))
        {
            $file = $this->files[$name];
            $size = $file['size'];

            if (!$bytes)
            {
                if ($size < 1024) return $size . "B";
                else $size = $size / 1024;

                if ($size < 1024) return round($size, 2) . "KB";
                else $size = $size / 1024;

                if ($size < 1024) return round($size, 2) . "MB";
                else $size = $size / 1024;

                if ($size < 1024) return round($size, 2) . "GB";
                else $size = $size / 1024;

                return round($size, 2) . "TB";
            }
            else 
            {
                return $size;
            }
        }
        else 
        {
            return 0;
        }
    }

    /**
     * Establishes a size limit for the uploader. Size can be labeled such as 5GB, 2MB, or 3KB.
     *
     * @param mixed $size
     * @return void
     */
    public function limitSize($size)
    {
        $l2c = strtolower(substr($size, -2));

        if (ctype_alpha($l2c))
        {
            $i = 0;
            $num = (int)$size;

            switch ($l2c)
            {
                case 'kb':
                    $i = 1;
                    break;
                case 'mb':
                    $i = 2;
                    break;
                case 'gb':
                    $i = 3;
                    break;
                case 'tb':
                    $i = 4;
                    break;
                default:
                    // Error 
                    die('Invalid size unit');
                    break;
            }

            for ($i; $i > 0; $i--)
            {
                $num *= 1024;
            }

            $this->size_limit = $num;
        }
        else 
        {
            $this->size_limit = $size;
        }
    }

    /**
     * Sets the upload directory. Directory must already exist otherwise error will be thrown.
     *
     * @param string $upload_dir
     * @return void
     */
    public function setUploadDir(string $upload_dir)
    {
        if (!is_dir($upload_dir))
        {
            throw new \Exception("Invalid directory <b>{$upload_dir}</b>.");
        }

        $this->upload_dir = $upload_dir;
    }

    /**
     * Uploads the file permanently to the server
     *
     * @param string $name
     * @return void
     */
    public function upload($name)
    {
        if (isset($this->files[$name]))
        {
            $errors    = [];
            $file      = $this->files[$name];
            $file_name = $file['name'];
            $file_tmp  = $file['tmp_name'];
            $file_type = $file['type'];
            $file_size = $file['size'];
            @$file_ext = strtolower(end(explode('.', $file['name'])));

            if (!$this->checkExt($file_ext))
            {
                $errors[] = "Error uploading file: Illegal file extension <b>{$file_ext}</b>. Allowed extensions: <i>" . implode(', ', $this->allowed_extensions) . "</i>.";
            }

            if (!$this->checkSize($file_size))
            {
                $errors[] = "Error uploading file: File size too big. Must be smaller than <b>{$this->size_limit}B</b>.";
            }

            if ($errors == [])
            {
                $newname = substr(hash("sha256", $file_name . mt_rand() . $file_size . $file_ext), 0, 16) . ".{$file_ext}";

                copy($file_tmp, $this->upload_dir . DS . $newname);
            }
            else 
            {
                die(print_r($errors, true));
            }
        }
        else 
        {
            return false;
        }
    }

    /**
     * Used by upload() method to check and make sure the extension is allowed. 
     *
     * @param string $ext
     * @return bool
     */
    private function checkExt($ext)
    {
        return in_array(strtolower($ext), $this->allowed_extensions);
    }

    /**
     * Used by upload() method to check and make sure the size is valid.
     *
     * @param int $size
     * @return bool
     */
    private function checkSize($size)
    {
        return (bool) ($size < $this->size_limit);
    }

}