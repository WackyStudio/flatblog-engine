<?php
namespace WackyStudio\Flatblog\Builders;

use Interop\Container\ContainerInterface;
use WackyStudio\Flatblog\FileWriters\BuildFileWriter;

class Builder
{
    private $builders = [
        PagesBuilder::class,
        PostsBuilder::class
    ];
    /**
     * @var BuildFileWriter
     */
    private $fileWriter;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(BuildFileWriter $fileWriter, ContainerInterface $container)
    {
        $this->fileWriter = $fileWriter;
        $this->container = $container;
    }

    public function build()
    {
        $entities = collect($this->builders)->transform(function($builderClass){
            return ($this->container->get($builderClass))->build();
        })->flatten(1)->toArray();

        $this->fileWriter->writeMultipleFiles($entities, '');
    }

}