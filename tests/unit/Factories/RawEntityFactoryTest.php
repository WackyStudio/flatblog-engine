<?php
use Carbon\Carbon;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\Settings\SettingsParser;
use WackyStudio\Flatblog\Settings\SettingsReferencesHandler;

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
    *@test
    */
    public function it_creates_an_array_of_raw_entities_for_directory()
    {
        $this->fileSystem = $this->createVirtualFilesystemForPosts();

        $settingsParser = Mockery::mock(SettingsParser::class)->shouldReceive('parse')->andReturn(['title'=>'test'])->getMock();
        $fileHandler = new RawEntityFactory($this->fileSystem, $settingsParser);

        $rawEntities = $fileHandler->getEntitiesForDirectory('posts');

        $this->assertEquals(2, count($rawEntities));
        $this->assertInstanceOf(RawEntity::class, $rawEntities[0]);
        $this->assertEquals('posts/Frontend/sass-tricks-you-should-know', $rawEntities[1]->getPath());
        $this->assertArrayHasKey('title', $rawEntities[0]->getSettings());
    }

    /**
    *@test
    */
    public function it_walks_through_directories_recursive_to_find_settings_file_an_therefore_a_raw_entity()
    {
        $this->fileSystem = $this->createVirtualFilesystemForPages();
        $settingsParser = new SettingsParser($this->fileSystem, new SettingsReferencesHandler($this->fileSystem));
        $fileHandler = new RawEntityFactory($this->fileSystem, $settingsParser);

        $rawFileEntities = $fileHandler->getEntitiesForDirectory('pages');

        $this->assertEquals(4, count($rawFileEntities));
        $this->assertInstanceOf(RawEntity::class, $rawFileEntities[0]);
        $this->assertEquals('pages/about', $rawFileEntities[0]->getPath());
        $this->assertArrayHasKey('header', $rawFileEntities[0]->getSettings());
    }
}