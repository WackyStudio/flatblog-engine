<?php
namespace WackyStudio\Flatblog\Entities;

use Carbon\Carbon;

class RawEntity
{

    private $path;
    /**
     * @var array
     */
    private $settings;
    /**
     * @var array
     */
    private $files;

    /**
     * @var array
     */
    private $subDirs;
    /**
     * @var Carbon
     */
    private $dateTime;

    public function __construct($path, array $settings, array $files, Carbon $dateTime)
    {
        $this->path = $path;
        $this->settings = $settings;
        $this->files = $files;
        $this->dateTime = $dateTime;

        $this->subDirs = explode('/', $path);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getSubDirectories()
    {
        return $this->subDirs;
    }

    /**
     * @return Carbon
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }
}