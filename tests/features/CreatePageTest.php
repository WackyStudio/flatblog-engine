<?php
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;
use WackyStudio\Flatblog\Commands\CreatePage;

class CreatePageTest extends TestCase
{
    /** @test */
    public function it_creates_a_new_page()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };

        $command = new CreatePage();

        $pageName = 'Test Page';
        $output = Mockery::mock(OutputInterface::class)->makePartial()->shouldReceive('writeln')->getMock();
        $command($pageName, $output);

        $this->assertTrue($fileSystem->has('pages/test-page/settings.yml'));
    }
}