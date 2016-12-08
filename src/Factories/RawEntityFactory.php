<?php
namespace WackyStudio\Flatblog\Factories;

use Carbon\Carbon;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Parsers\SettingsParser;

class RawEntityFactory
{

    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var SettingsParser
     */
    private $settingsParser;

    public function __construct(Filesystem $filesystem, SettingsParser $settingsParser)
    {
        $this->filesystem = $filesystem;
        $this->settingsParser = $settingsParser;
    }

    /**
     * Get raw file entities for a root directory
     *
     * @param $directory
     *
     * @return array
     */
    public function getEntitiesForDirectory($directory)
    {
        return collect($this->filesystem->listContents($directory, true))
            ->filter(function ($item) {
                // Filter files from directories
                return $item['type'] !== 'dir';
            })
            ->groupBy(function ($item) {
                // Group files with same directory
                return $item['dirname'];
            })
            ->filter(function ($item) {
                // Filter directories that doesn't have a settings.yml file
                return $item->contains(function ($key, $file) {
                    return $file['basename'] == 'settings.yml';
                });
            })
            ->flatMap(function ($item, $key) {
                // Map directories with files for entities into RawEntity objects
                $settingsFile = $this->getSettingsFileFromFilesArray($item);
                $otherFiles = $this->getAllFilesExceptSettingsFile($item);

                return [
                    new RawEntity(
                        $key,
                        $this->settingsParser->parse($settingsFile),
                        $otherFiles,
                        Carbon::createFromTimestamp($settingsFile['timestamp']))
                ];
            })
            ->toArray();
    }

    /**
     * Filter other files from directory and get settings.yml file
     *
     * @param $files
     *
     * @return array
     */
    private function getSettingsFileFromFilesArray($files)
    {
        return collect($files)
            ->where('basename', 'settings.yml')
            ->first();
    }

    /**
     * Filter settings.yml from directory and map all other files
     * to File objects
     *
     * @param $files
     *
     * @return array
     */
    private function getAllFilesExceptSettingsFile($files)
    {
        return collect($files)
            ->filter(function ($item) {
                return $item['basename'] !== 'settings.yml';
            })
            ->map(function($item){
                return new File($item['path'], $item['timestamp'], $item['size'], $item['dirname'], $item['basename'], $item['extension'], $item['filename']);
            })
            ->toArray();
    }
}