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
    /**
     * @var SettingsReferencesHandler
     */
    private $referencesHandler;

    public function __construct(Filesystem $fileSystem, SettingsReferencesHandler $referencesHandler)
    {
        $this->filesystem = $fileSystem;
        $this->referencesHandler = $referencesHandler;
    }

    public function parse($file)
    {
        $path = $this->getPathForSettingsFile($file);

        $settings = $this->parseYamlFile($file);
        $settings = $this->referencesHandler->handleFileReferences($settings, $path);
        $settings = $this->referencesHandler->handleDirectoryReferences($settings, $path);

        return $settings;
    }

    public function parseYamlFile($file)
    {
        if(!$this->filesystem->has($file))
        {
            throw new SettingsFileNotFoundException;
        }

        $file = $this->filesystem->read($file);
        return Yaml::parse($file);
    }

    public function getPathForSettingsFile($file)
    {
        return str_replace('/settings.yml', '', $file);
    }
}