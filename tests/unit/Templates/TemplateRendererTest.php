<?php
use duncan3dc\Laravel\BladeInstance;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

class TemplateRendererTest extends PHPUnit_Framework_TestCase
{

    private $viewPath;
    private $cachePath;

    public function setUp()
    {
        parent::setUp();

        $this->viewPath = __DIR__ . '/../../helpers/views';
        mkdir(__DIR__ . '/../../temp');
        $this->cachePath = __DIR__ . '/../../temp';

    }
    
    public function tearDown()
    {
        array_map('unlink', glob("__DIR__ . '/../../temp'/*.*"));
        rmdir(__DIR__ . '/../../temp');
        parent::tearDown();
    }
    
    /**
    * @test
    */
    public function it_renders_template_from_template_name_with_data()
    {
        $blade = new BladeInstance($this->viewPath, $this->cachePath);
        $renderer = new TemplateRenderer($blade);

        $content = $renderer->render('test', [
            'test' => 'HELLO WORLD'
        ]);

        $this->assertEquals('<h1>HELLO WORLD</h1>', $content);
    
    }

}