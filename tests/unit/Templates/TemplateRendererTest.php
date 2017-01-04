<?php
use duncan3dc\Laravel\BladeInstance;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

class TemplateRendererTest extends PHPUnit_Framework_TestCase
{

    private $viewPath;
    private $cachePath;
    /** @var  Filesystem */
    private $filesystem;

    public function setUp()
    {
        parent::setUp();

        $localAdapter = new Local(__DIR__ . '/../../');
        $this->filesystem = new Filesystem($localAdapter);

        $this->filesystem->createDir('temp');

        $this->viewPath = __DIR__ . '/../../helpers/views';
        $this->cachePath = __DIR__ . '/../../temp';

    }
    
    public function tearDown()
    {

        $this->filesystem->deleteDir('temp');
        parent::tearDown();
    }
    
    /**
    * @test
    */
    public function it_renders_template_from_template_name_with_data()
    {
        $blade = new BladeInstance($this->viewPath, $this->cachePath);
        $config = Mockery::mock(Config::class)->shouldReceive('get')->with('someVar')->andReturn('test')->getMock();
        $renderer = new TemplateRenderer($blade, $config);

        $content = $renderer->render('test', [
            'test' => 'HELLO WORLD'
        ]);

        $this->assertEquals('<h1>HELLO WORLD - test</h1>', $content);
    
    }

}