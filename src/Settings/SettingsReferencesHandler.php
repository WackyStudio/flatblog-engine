<?php
namespace WackyStudio\Flatblog\Settings;

use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Parsers\ParserManager;

class SettingsReferencesHandler
{

    /**
     * @var Filesystem
     */
    private $filesystem;


    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function handleFileReferences($settingsContent, $settingsFilePath)
    {
        return collect($settingsContent)->transform(function ($setting) use($settingsFilePath){

            if(!is_array($setting) && str_contains($setting, 'file:'))
            {
                $fileBasename = explode('file:', $setting)[1];

                $file = collect($this->filesystem->listContents($settingsFilePath))->filter(function($file) use($fileBasename){
                    return ($file['basename'] == $fileBasename);
                })->first();

                return new File($file['path'], $file['timestamp'], $file['size'], $file['dirname'], $file['basename'], $file['extension'], $file['filename']);
            }

            if(is_array($setting))
            {
                return $this->handleFileReferences($setting, $settingsFilePath);
            }

            return $setting;
        })->toArray();
    }

    public function handleDirectoryReferences($settingsContent, $settingsFilePath)
    {
        return collect($settingsContent)->transform(function ($setting) use($settingsFilePath){

            if(!is_array($setting) && str_contains($setting, 'dir:'))
            {
                $dirBasename = explode('dir:', $setting)[1];

                $files = collect($this->filesystem->listContents($settingsFilePath."/{$dirBasename}"))->filter(function($item){
                    return $item['type'] == 'file';
                })->flatMap(function ($item) {
                    return [$item['filename'] => new File($item['path'], $item['timestamp'], $item['size'], $item['dirname'], $item['basename'], $item['extension'],
                        $item['filename'])];
                })->toArray();

                return $files;
            }

            if(is_array($setting))
            {
                return $this->handleDirectoryReferences($setting, $settingsFilePath);
            }

            return $setting;
        })->toArray();
    }

}