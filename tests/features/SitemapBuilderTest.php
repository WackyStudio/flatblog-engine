<?php
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\SitemapBuilder;
use WackyStudio\Flatblog\Factories\PageEntityFactory;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;

class SitemapBuilderTest extends TestCase
{
    /**
    *@test
    */
    public function it_creates_sitemap_for_site()
    {
        $fileSystem = $this->createVirtualFilesystemForPagesAndPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };

        $rawPostsEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');
        $rawPagesEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('pages');

        $builder = new SitemapBuilder($rawPostsEntities, $rawPagesEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[PageEntityFactory::class], $this->app->getContainer()['config'], $fileSystem);

        $builder->build();

        $this->assertTrue($fileSystem->has('build/sitemap.txt'));
        $this->assertEquals(implode(PHP_EOL, [
            'http://flatblog.dev/blog/backend/do-you-really-need-a-backend-for-that',
            'http://flatblog.dev/blog/frontend/sass-tricks-you-should-know',
            'http://flatblog.dev/blog',
            'http://flatblog.dev/blog/categories',
            'http://flatblog.dev/about',
            'http://flatblog.dev/parent/child/second-child',
            'http://flatblog.dev/parent/child',
            'http://flatblog.dev',
        ]), $fileSystem->read('build/sitemap.txt'));

    }
}