<?php
namespace WackyStudio\Flatblog\Parsers;

use Illuminate\Support\Collection;
use Interop\Container\ContainerInterface;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\File;

class ParserManager
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

    /**
     * Run files through content parsers by file extensions
     * and return array with filename as key pointing to parsed content
     *
     * @param array $files
     *
     * @return array
     */
    public function parseFiles(array $files)
    {
        $filesSortedByExtension = collect($files)->groupBy(function (File $file) {
            return $file->getExtension();
        });

        return $filesSortedByExtension->flatMap(function ($files, $extension) {
            if ($this->contentParsers->has($extension)) {
                return $files->flatMap(function (File $file) use ($extension) {
                    return [$file->getFilename() => $this->sendFileThroughParsersForExtension($extension, $file)];
                });
            }
        })->toArray();
    }

    /**
     * Send file content through every content parser for files extension
     *
     * @param $extension
     * @param File $file
     *
     * @return bool|false|string
     */
    private function sendFileThroughParsersForExtension($extension, File $file)
    {
        $fileContent = $this->getFileContentForFilePath($file->getPath());
        $parsers = $this->contentParsers->get($extension);

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