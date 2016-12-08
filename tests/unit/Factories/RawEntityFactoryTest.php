<?php
use Carbon\Carbon;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\Parsers\SettingsParser;

class RawEntityFactoryTest extends TestCase
{

    private $fileSystem;

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
    public function it_creates_an_array_of_file_entities_for_posts()
    {
        $this->fileSystem = $this->createVirtualFilesystemForPosts();

        $settingsParser = Mockery::mock(SettingsParser::class)->shouldReceive('parse')->andReturn(['title'=>'test'])->getMock();
        $fileHandler = new RawEntityFactory($this->fileSystem, $settingsParser);

        $postFileEntities = $fileHandler->getEntitiesForDirectory('posts');

        $this->assertEquals(2, count($postFileEntities));
        $this->assertInstanceOf(RawEntity::class, $postFileEntities[0]);
        $this->assertEquals('posts/Frontend/sass-tricks-you-should-know', $postFileEntities[1]->getPath());

        $timestamp = $this->fileSystem->listContents('/posts/Backend/do-you-really-need-a-backend-for-that')[0]['timestamp'];
        $this->assertEquals(Carbon::createFromTimestamp($timestamp), $postFileEntities[0]->getDateTime());
        $this->assertInstanceOf(File::class, $postFileEntities[0]->getFiles()[0]);
    }

    /**
    *@test
    */
    public function it_creates_an_array_of_file_entities_for_pages()
    {
        $this->fileSystem = $this->createVirtualFilesystemForPages();
        $settingsParser = new SettingsParser($this->fileSystem);
        $fileHandler = new RawEntityFactory($this->fileSystem, $settingsParser);

        $pagesFileEntities = $fileHandler->getEntitiesForDirectory('pages');

        $this->assertEquals(4, count($pagesFileEntities));
        $this->assertInstanceOf(RawEntity::class, $pagesFileEntities[0]);
        $this->assertEquals('pages/about', $pagesFileEntities[0]->getPath());
        $this->assertInstanceOf(File::class, $pagesFileEntities[0]->getFiles()[0]);
    }





}