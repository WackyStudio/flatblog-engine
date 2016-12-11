<?php
namespace WackyStudio\Flatblog\Factories;

use Carbon\Carbon;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Parsers\ContentParserManager;
use WackyStudio\Flatblog\Settings\SettingsParser;

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
    /**
     * @var ContentParserManager
     */
    private $contentParserManager;

    public function __construct(Filesystem $filesystem, SettingsParser $settingsParser, ContentParserManager $contentParserManager)
    {
        $this->filesystem = $filesystem;
        $this->settingsParser = $settingsParser;
        $this->contentParserManager = $contentParserManager;
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
                $parsedSettingsFile =  $this->settingsParser->parse($settingsFile['path']);
                $settingsFileWithParsedContent = $this->contentParserManager->parseContentFromSettings($parsedSettingsFile);

                return [
                    new RawEntity(
                        $key,
                        $settingsFileWithParsedContent,
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

}