<?php
use WackyStudio\Flatblog\Exceptions\SettingsFileNotFoundException;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Settings\SettingsImportsHandler;
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

        $settingsParser = new SettingsParser($filesystem, new SettingsReferencesHandler($filesystem), new SettingsImportsHandler($filesystem));

        $parsedContent = $settingsParser->parseYamlFile('posts/Back end/do-you-really-need-a-backend-for-that/settings.yml');

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
            $settingsParser = new SettingsParser($filesystem, new SettingsReferencesHandler($filesystem), new SettingsImportsHandler($filesystem));
            $parsedContent = $settingsParser->parseYamlFile('posts/Back end/do-you-really-need-a-backend-for-that/settings.yml');
        }catch (SettingsFileNotFoundException $e)
        {

            return;
        }

        $this->fail('SettingsFileNotFoundException was not thrown, when a settings file is not found');
    }

    /**
    *@test
    */
    public function it_gets_path_for_settings_file()
    {
        $filesystem = $this->createVirtualFilesystemForPosts();

        $settingsParser = new SettingsParser($filesystem, new SettingsReferencesHandler($filesystem), new SettingsImportsHandler($filesystem));

        $path = $settingsParser->getPathForSettingsFile('posts/Backend/do-you-really-need-a-backend-for-that/settings.yml');

        $this->assertEquals('posts/Backend/do-you-really-need-a-backend-for-that', $path);
    }

    /**
    *@test
    */
    public function it_sends_settings_array_through_settings_references_handler()
    {
        $filesystem = $this->createVirtualFilesystemForPosts();

        $settingsParser = new SettingsParser($filesystem, new SettingsReferencesHandler($filesystem), new SettingsImportsHandler($filesystem));

        $parsedContent = $settingsParser->parse('posts/Back end/do-you-really-need-a-backend-for-that/settings.yml');

        $this->assertArrayHasKey('content', $parsedContent);
        $this->assertInstanceOf(File::class, $parsedContent['content']);

    }

    /** @test */
    public function it_can_handle_imports_of_other_yaml_files()
    {
        $filesystem = $this->createVirtualFilesystemForPosts();

        $settingsParser = new SettingsParser($filesystem, new SettingsReferencesHandler($filesystem), new SettingsImportsHandler($filesystem));

        $parsedContent = $settingsParser->parse('posts/Back end/we-also-support-imports-in-posts-now/settings.yml');

        $this->assertArrayHasKey('result', $parsedContent);
        $this->assertEquals('it works perfectly', $parsedContent['result']);
    }

}