<?php
namespace WackyStudio\Flatblog\Builders;

use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Contracts\BuilderContract;
use WackyStudio\Flatblog\Entities\RawEntity;

class ImageFolderBuilder implements BuilderContract
{

    /**
     * @var array
     */
    private $rawEntities;
    /**
     * @var Filesystem
     */
    private $fileSystem;

    private $imagesFileTypes = [
        'jpg',
        'png',
        'gif',
        'bmp',
        'svg'
    ];

    public function __construct(array $rawEntities, Filesystem $fileSystem)
    {
        $this->rawEntities = $rawEntities;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @inheritdoc
     */
    public function build()
    {
        $images = collect($this->rawEntities)->flatMap(function (RawEntity $rawEntity) {
            return collect($this->fileSystem->listContents($rawEntity->getPath().'/images'));
        })->filter(function($file){
            return $file['type'] !== 'dir';
        })->filter(function($file){
            return collect($this->imagesFileTypes)->contains($file['extension']);
        })->each(function ($image) {

            $destination = "build/images/{$image['basename']}";

            if(!$this->fileSystem->has($destination))
            {
                $this->fileSystem->copy($image['path'], $destination);
            }
        });
    }
}