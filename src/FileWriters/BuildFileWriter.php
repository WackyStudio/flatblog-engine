<?php
namespace WackyStudio\Flatblog\FileWriters;

use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Exceptions\KeyMissingInFileArrayException;

class BuildFileWriter
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function writeSingleFile($url, $content, $prefix = '')
    {
        $buildUrl = $this->makeFilePath($url, $prefix);

        $this->filesystem->put($buildUrl, $content);
    }

    /**
     * @param $url
     * @param $prefix
     *
     * @return string
     */
    protected function makeFilePath($url, $prefix)
    {
        $buildPieces = ['build'];
        if ($prefix !== '') {
            $buildPieces[] = $prefix;
        }
        $buildPieces[] = $url;
        $buildPieces[] = 'index.html';
        $buildUrl = rtrim(implode('/', $buildPieces), '/');

        return $buildUrl;
    }

    public function writeMultipleFiles(array $entities, $prefix)
    {
        foreach ($entities as $url => $content)
        {
            if(!is_string($url))
            {
                throw new KeyMissingInFileArrayException('No key in form of URL path, was given in array');
            }

            $this->writeSingleFile($url, $content, $prefix);
        }
    }
}