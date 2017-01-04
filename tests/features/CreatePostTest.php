<?php
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;
use WackyStudio\Flatblog\Commands\CreatePost;
use WackyStudio\Flatblog\Makers\PostMaker;

class CreatePostTest extends TestCase
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
    * @test
    */
    public function it_creates_a_new_post()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();

        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };

        $postMaker = $this->app->getContainer()[PostMaker::class];
        
        $command = new CreatePost();
        $output = Mockery::mock(OutputInterface::class)->makePartial()->shouldReceive('writeln')->getMock();
        $command('Test Post', 'Test Category', $output, $postMaker);
        
        $this->assertTrue($fileSystem->has('/posts/test-category/test-post/settings.yml'));
    }

}