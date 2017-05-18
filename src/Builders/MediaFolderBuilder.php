<?php

namespace WackyStudio\Flatblog\Builders;

use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Contracts\BuilderContract;

class MediaFolderBuilder implements BuilderContract
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @inheritdoc
     */
    public function build()
    {
        collect($this->filesystem->listContents('media'))->each(function ($item){
            $this->filesystem->putStream("build/media/{$item['basename']}", $this->filesystem->readStream($item['path']));
        });
    }
}