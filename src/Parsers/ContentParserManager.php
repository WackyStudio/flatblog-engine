<?php
namespace WackyStudio\Flatblog\Parsers;

use Illuminate\Support\Collection;
use Interop\Container\ContainerInterface;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\File;

class ContentParserManager
{
    /**
     * @var Collection
     */
    private $contentParsers;

    /**
     * @var Filesystem
     */

    private $filesystem;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(array $contentParsers, Filesystem $filesystem, ContainerInterface $container)
    {
        $this->contentParsers = collect($contentParsers);
        $this->filesystem = $filesystem;
        $this->container = $container;
    }

    public function parseContentFromSettings($settingsFile)
    {
        return collect($settingsFile)->transform(function ($item) {
           if($item instanceof File)
           {
               if ($this->contentParsers->has($item->getExtension())) {
                   return $this->sendFileThroughParsersForExtension($item);
               }

               return $item;
           }

           if(is_array($item))
           {
               return $this->parseContentFromSettings($item);
           }

           return $item;
        })->toArray();
    }

    /**
     * Send file content through every content parser for files extension
     *
     * @param File $file
     *
     * @return bool|false|string
     */
    private function sendFileThroughParsersForExtension(File $file)
    {
        $fileContent = $this->getFileContentForFilePath($file->getPath());
        $parsers = $this->contentParsers->get($file->getExtension());

        if(!is_array($parsers))
        {
            $parsers = [$parsers];
        }

        foreach ($parsers as $parser) {
            $fileContent = ($this->container->get($parser))->parse($fileContent);
        }

        return $fileContent;
    }

    /**
     * Read contents of file in path
     *
     * @param $path
     *
     * @return bool|false|string
     */
    private function getFileContentForFilePath($path)
    {
        return $this->filesystem->read($path);
    }

}