<?php
namespace WackyStudio\Flatblog\Builders;

use Interop\Container\ContainerInterface;
use WackyStudio\Flatblog\FileWriters\BuildFileWriter;

class Builder
{
    private $buildersWithContentToBeWritten = [
        PagesBuilder::class,
        PostsBuilder::class,
    ];

    private $buildersWithOwnWriters = [
        ImageFolderBuilder::class,
        SitemapBuilder::class,
        MediaFolderBuilder::class,
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
        $this->clearBuildFolder();
        $this->runBuildersAndWriteContentsToBuildFolder();
        $this->runBuildersWithOwnFileWriter();
    }

    protected function runBuildersAndWriteContentsToBuildFolder()
    {
        $this->fileWriter->writeMultipleFiles(collect($this->buildersWithContentToBeWritten)
            ->flatMap(function ($builderClass) {
                return ( $this->container->get($builderClass) )->build();
            })
            ->toArray());
    }

    protected function runBuildersWithOwnFileWriter()
    {
        collect($this->buildersWithOwnWriters)->each(function ($builderClass) {
            ( $this->container->get($builderClass) )->build();
        });
    }

    private function clearBuildFolder()
    {
        $this->fileWriter->removeBuildFiles();
    }

}