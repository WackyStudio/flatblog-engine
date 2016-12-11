<?php
use duncan3dc\Laravel\BladeInstance;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\PostsBuilder;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
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
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], [
            'single' => 'single'
        ]);

        $expectedContentForBackendPost = implode(PHP_EOL, [
            '<h1>Do you really need a backend for that?</h1>',
            '<h2>Hello World 2</h2>'
        ]);

        $expectedContentForFrontendPost = implode(PHP_EOL, [
            '<h1>Sass tricks you should know!</h1>',
            '<h2>Hello World</h2>'
        ]);

        $singlePosts = $postBuilder->buildSinglePosts();

        $this->assertEquals($expectedContentForBackendPost, $singlePosts['posts/backend/do-you-really-need-a-backend-for-that']);
        $this->assertEquals($expectedContentForFrontendPost, $singlePosts['posts/frontend/sass-tricks-you-should-know']);

    }
}