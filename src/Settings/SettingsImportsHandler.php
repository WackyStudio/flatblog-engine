<?php

namespace WackyStudio\Flatblog\Settings;

use League\Flysystem\Filesystem;
use SelvinOrtiz\Utils\Flux\Flux;
use WackyStudio\Flatblog\Exceptions\ImportedFileInSettingsFileNotFound;
use WackyStudio\Flatblog\Exceptions\SettingsFileNotFoundException;

class SettingsImportsHandler
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {

        $this->filesystem = $filesystem;
    }

    public function handleImports($settingsFilePath)
    {
        if(!$this->filesystem->has($settingsFilePath)){
            throw new SettingsFileNotFoundException;
        }

        $folderPath = str_replace('settings.yml', '', $settingsFilePath);

        $contents = $this->filesystem->read($settingsFilePath);


        $flux = Flux::getInstance()
            ->find('@import(')->anything()->then(')')->ignoreCase()->oneLine();

        $result = preg_replace_callback($flux->getPattern(), function($item) use($folderPath){

            if(!$this->filesystem->has($folderPath . $item[2])){
                throw new ImportedFileInSettingsFileNotFound($folderPath . $item[2]);
            }

            return $this->filesystem->read($folderPath . $item[2]);
        }, $contents);

        $this->filesystem->update($settingsFilePath, $result);
    }
}