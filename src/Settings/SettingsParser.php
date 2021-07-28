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

    /** @var SettingsImportsHandler */
    private $importsHandler;

    public function __construct(Filesystem $fileSystem, SettingsReferencesHandler $referencesHandler, SettingsImportsHandler  $importsHandler)
    {
        $this->filesystem = $fileSystem;
        $this->referencesHandler = $referencesHandler;
        $this->importsHandler = $importsHandler;
    }

    public function parse($file)
    {
        $path = $this->getPathForSettingsFile($file);

        $settings = $this->handleImportsBeforeParsing($file);

        $settings = $this->parseYamlFile($settings, true);
        $settings = $this->referencesHandler->handleFileReferences($settings, $path);
        $settings = $this->referencesHandler->handleDirectoryReferences($settings, $path);

        return $settings;
    }

    public function parseYamlFile($fileOrFileContents, bool $isFileContents = false)
    {
        if(!$isFileContents){
            if(!$this->filesystem->has($fileOrFileContents))
            {
                throw new SettingsFileNotFoundException;
            }

            $fileOrFileContents = $this->filesystem->read($fileOrFileContents);
        }

        return Yaml::parse($fileOrFileContents);
    }

    public function getPathForSettingsFile($file)
    {
        return str_replace('/settings.yml', '', $file);
    }

    public function handleImportsBeforeParsing($file)
    {

        if(!$this->filesystem->has($file))
        {
            throw new SettingsFileNotFoundException;
        }

        return $this->importsHandler->handleImports($file);
    }


}