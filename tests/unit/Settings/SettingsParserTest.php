<?php
use WackyStudio\Flatblog\Exceptions\SettingsFileNotFoundException;
use WackyStudio\Flatblog\Settings\SettingsParser;
use WackyStudio\Flatblog\Settings\SettingsReferencesHandler;

class SettingsParserTest extends TestCase
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
    public function it_parses_settings_from_yaml_file_path()
    {
        $filesystem = $this->createVirtualFilesystemForPosts();

        $settingsParser = new SettingsParser($filesystem, new SettingsReferencesHandler($filesystem));

        $parsedContent = $settingsParser->parseYamlFile(['path' => 'posts/Backend/do-you-really-need-a-backend-for-that/settings.yml']);

        $this->assertArrayHasKey('title', $parsedContent);

    }

    /**
    *@test
    */
    public function it_can_not_parse_file_that_does_not_exist()
    {
        $filesystem = $this->createVirtualFilesystemForPosts([
            'posts' => []
        ]);

        try{
            $settingsParser = new SettingsParser($filesystem, new SettingsReferencesHandler($filesystem));
            $parsedContent = $settingsParser->parseYamlFile(['path'=>'posts/Backend/do-you-really-need-a-backend-for-that/settings.yml']);
        }catch (SettingsFileNotFoundException $e)
        {

            return;
        }

        $this->fail('SettingsFileNotFoundException was not thrown, when a settings file is not found');
    }

}