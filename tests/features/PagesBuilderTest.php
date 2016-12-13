<?php
use duncan3dc\Laravel\BladeInstance;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\PagesBuilder;
use WackyStudio\Flatblog\Factories\PageEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

class PagesBuilderTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }
    
    /**
    *@test
    */
    public function it_builds_pages()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('pages');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $pagesBuilder = new PagesBuilder($rawEntities, $this->app->getContainer()[PageEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $pages = $pagesBuilder->build();

        $expectedContent = implode(PHP_EOL, [
           '<h1>Test header</h1>'
        ]);

        $this->assertEquals($expectedContent, $pages['about']);

    }

}