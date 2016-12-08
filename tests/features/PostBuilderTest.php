<?php
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\PostsBuilder;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\Parsers\SettingsParser;

class PostBuilderTest extends TestCase
{
    /**
    *@test
    */
    public function it_builds_single_posts()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
          return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');
        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class]);

        $singlePosts = $postBuilder->buildSinglePosts();

    }
}