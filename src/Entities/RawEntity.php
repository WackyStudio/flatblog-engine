<?php
namespace WackyStudio\Flatblog\Entities;

use Ausi\SlugGenerator\SlugGenerator;
use Ausi\SlugGenerator\SlugOptions;
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
        $generator = new SlugGenerator((new SlugOptions)
            ->setValidChars('a-z0-9/')
            ->setPreTransforms([
                'æ > ae',
                'ø > oe',
                'å > aa',
            ])
            ->setLocale('da')
        );

        $this->path = $generator->generate($path);
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