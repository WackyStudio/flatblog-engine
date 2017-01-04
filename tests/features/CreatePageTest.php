<?php
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;
use WackyStudio\Flatblog\Commands\CreatePage;
use WackyStudio\Flatblog\Makers\PageMaker;

class CreatePageTest extends TestCase
{
    /** @test */
    public function it_creates_a_new_page()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };

        $pageMaker = $this->app->getContainer()[PageMaker::class];

        $command = new CreatePage();

        $pageName = 'Test Page';
        $parentPage = null;
        $template = null;

        $output = Mockery::mock(OutputInterface::class)->makePartial()->shouldReceive('writeln')->getMock();
        $command($pageName, $parentPage, $template, $output, $pageMaker);

        $this->assertTrue($fileSystem->has('pages/test-page/settings.yml'));
    }
}