<?php
namespace WackyStudio\Flatblog;

use Carbon\Carbon;

class File
{

    /**
     * @var string
     */
    private $path;

    /**
     * @var Carbon
     */
    private $timestamp;

    /**
     * @var integer
     */
    private $size;

    /**
     * @var string
     */
    private $dirname;

    /**
     * @var string
     */
    private $basename;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $filename;

    public function __construct($path, $timestamp, $size, $dirname, $basename, $extension, $filename)
    {
        $this->path = $path;
        $this->timestamp = Carbon::createFromTimestamp($timestamp);
        $this->size = $size;
        $this->dirname = $dirname;
        $this->basename = $basename;
        $this->extension = $extension;
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return Carbon
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * @return string
     */
    public function getBasename()
    {
        return $this->basename;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }


}