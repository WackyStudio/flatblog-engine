<?php
use duncan3dc\Laravel\BladeInstance;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\Builder;
use WackyStudio\Flatblog\FileWriters\BuildFileWriter;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

class BuilderTest extends TestCase
{

    /**
     * @var Filesystem
     */
    private $fileSystem;

    public function setUp()
    {
        parent::setUp();
        $this->fileSystem = $this->createVirtualFilesystemForPagesAndPosts();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }
    
    /**
    * @test
    */
    public function it_runs_through_all_builders_and_builds_site()
    {
        $this->app->getContainer()[Filesystem::class] = function() {
            return $this->fileSystem;
        };

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $builder = new Builder($this->app->getContainer()[BuildFileWriter::class], $this->app->getContainer());

        $builder->build();

        $this->assertTrue($this->fileSystem->has('build'));
        $this->assertTrue($this->fileSystem->has('build/index.html'));
        $this->assertTrue($this->fileSystem->has('build/about/index.html'));
        $this->assertTrue($this->fileSystem->has('build/parent/child/index.html'));
        $this->assertTrue($this->fileSystem->has('build/parent/child/second-child/index.html'));
        $this->assertTrue($this->fileSystem->has('build/blog/index.html'));
        $this->assertTrue($this->fileSystem->has('build/blog/categories/index.html'));
        $this->assertTrue($this->fileSystem->has('build/blog/frontend/index.html'));
        $this->assertTrue($this->fileSystem->has('build/blog/backend/index.html'));
        $this->assertTrue($this->fileSystem->has('build/blog/frontend/sass-tricks-you-should-know/index.html'));
        $this->assertTrue($this->fileSystem->has('build/blog/backend/do-you-really-need-a-backend-for-that/index.html'));
        $this->assertTrue($this->fileSystem->has('build/images/someimage.jpg'));
        $this->assertTrue($this->fileSystem->has('build/images/second-child-image.bmp'));
        $this->assertTrue($this->fileSystem->has('build/images/childImage.gif'));
        $this->assertTrue($this->fileSystem->has('build/images/parentImage.png'));
        $this->assertTrue($this->fileSystem->has('build/images/aboutImage.jpg'));
    }

    /**
    *@test
    */
    public function it_clears_build_dir_before_build()
    {
        $this->fileSystem->write('build/testClearing.txt', 'some content');
        $this->fileSystem->write('build/css/style.css', 'css');
        $this->fileSystem->write('build/js/app.js', 'js');

        $this->app->getContainer()[Filesystem::class] = function() {
            return $this->fileSystem;
        };

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $builder = new Builder($this->app->getContainer()[BuildFileWriter::class], $this->app->getContainer());

        $builder->build();

        $this->assertFalse($this->fileSystem->has('build/testClearing.txt'));
        $this->assertTrue($this->fileSystem->has('build/css/style.css'));
        $this->assertTrue($this->fileSystem->has('build/js/app.js'));
    }

}