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

    public function writeSingleFile($url, $content)
    {
        $buildUrl = $this->makeFilePath($url);

        $this->filesystem->put($buildUrl, $content);
    }

    /**
     * @param $url
     *
     * @return string
     */
    protected function makeFilePath($url)
    {
        $buildPieces = ['build'];

        if($url !== '_frontpage')
        {
            $buildPieces[] = $url;
        }

        $buildPieces[] = 'index.html';
        $buildUrl = rtrim(implode('/', $buildPieces), '/');

        return $buildUrl;
    }

    public function writeMultipleFiles(array $entities)
    {
        foreach ($entities as $url => $content)
        {
            if(!is_string($url))
            {
                throw new KeyMissingInFileArrayException('No key in form of URL path, was given in array');
            }

            $this->writeSingleFile($url, $content);
        }
    }

    public function removeBuildFiles()
    {
        if($this->filesystem->has('build'))
        {
            collect($this->filesystem->listContents('build', true))->filter(function($file){
                return $file['type'] == 'file';
            })->each(function ($file) {
                if($file['extension'] !== 'js' &&
                   $file['extension'] !== 'css' &&
                   $file['extension'] !== 'eot' &&
                   $file['extension'] !== 'svg' &&
                   $file['extension'] !== 'ttf' &&
                   $file['extension'] !== 'woff' &&
                   $file['extension'] !== 'woff2' &&
                    $file['extension'] !== 'webmanifest'){
                    $this->filesystem->delete($file['path']);
                }
            });
        }
    }
}