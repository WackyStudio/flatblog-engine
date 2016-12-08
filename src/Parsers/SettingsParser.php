<?php
namespace WackyStudio\Flatblog\Parsers;

use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use WackyStudio\Flatblog\Exceptions\SettingsFileNotFoundException;

class SettingsParser
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->filesystem = $fileSystem;
    }

    public function parse($file)
    {
        if(!$this->filesystem->has($file['path']))
        {
            throw new SettingsFileNotFoundException;
        }

        $file = $this->filesystem->read($file['path']);
        return Yaml::parse($file);
    }
}