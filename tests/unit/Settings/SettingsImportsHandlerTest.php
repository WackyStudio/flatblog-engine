<?php

use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use VirtualFileSystem\FileSystem as Vfs;
use WackyStudio\Flatblog\Settings\SettingsImportsHandler;

class SettingsImportsHandlerTest extends TestCase
{


    /** @test */
    public function it_replaces_import_statements_with_contents_of_imported_files()
    {
        $filesystem = $this->createVirtualFilesystemForPosts();

        $settingsFilePath = '/posts/Back end/we-also-support-imports-in-posts-now/settings.yml';

        $settingsImporter = new SettingsImportsHandler($filesystem);
        $settingsImporter->handleImports($settingsFilePath);

        $yamlAfterImport = Yaml::parse($filesystem->read($settingsFilePath));

        $this->assertEquals('it works perfectly', $yamlAfterImport['result']);
        $this->assertEquals('hello from partial', $yamlAfterImport['partial']);
    }


}