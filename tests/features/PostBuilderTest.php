<?php
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\PostsBuilder;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\Parsers\SettingsParser;
use WackyStudio\Flatblog\Templates\FakePostTemplateRenderer;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

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

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            return new TemplateRenderer(new FakePostTemplateRenderer);
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class] );

        $singlePosts = $postBuilder->buildSinglePosts();

        $this->assertEquals('<h1>Do you really need a backend for that?</h1>', $singlePosts['posts/backend/do-you-really-need-a-backend-for-that']);
        $this->assertEquals('<h1>Sass tricks you should know!</h1>', $singlePosts['posts/frontend/sass-tricks-you-should-know']);

    }
}