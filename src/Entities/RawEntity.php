<?php
namespace WackyStudio\Flatblog\Entities;

use Cake\Chronos\Chronos;

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
    private $subDirs;

    /**
     * @var Chronos
     */
    private $dateTime;

    public function __construct($path, array $settings, $dateTime)
    {
        $this->path = $path;
        $this->settings = $settings;
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