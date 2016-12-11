<?php
namespace WackyStudio\Flatblog\Settings;

use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use WackyStudio\Flatblog\Exceptions\SettingsFileNotFoundException;

class SettingsParser
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $fileSystem, SettingsReferencesHandler $referencesHandler)
    {
        $this->filesystem = $fileSystem;
    }

    public function parse($file)
    {
       $yamlContent = $this->parseYamlFile($file);

       return $yamlContent;
    }

    public function parseYamlFile($file)
    {
        if(!$this->filesystem->has($file['path']))
        {
            throw new SettingsFileNotFoundException;
        }

        $file = $this->filesystem->read($file['path']);
        return Yaml::parse($file);
    }
}