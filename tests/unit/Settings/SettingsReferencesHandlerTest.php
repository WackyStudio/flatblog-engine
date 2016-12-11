<?php
use WackyStudio\Flatblog\Exceptions\DirectoryReferenceInSettingsNotFoundException;
use WackyStudio\Flatblog\Exceptions\FileReferencedInSettingsNotFoundException;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Parsers\ParserManager;
use WackyStudio\Flatblog\Settings\SettingsReferencesHandler;

class SettingsReferencesHandlerTest extends TestCase
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
    *@test
    */
    public function it_goes_through_settings_array_to_find_references_to_files()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $settingsContent = [
            'content' => 'file:content.md'
        ];
        $settingsFilePath = '/posts/Frontend/sass-tricks-you-should-know';
        $settingsRefHandler = new SettingsReferencesHandler($fileSystem);

        $settingsWithParsedFileReferences = $settingsRefHandler->handleFileReferences($settingsContent, $settingsFilePath);

        $this->assertInstanceOf(File::class, $settingsWithParsedFileReferences['content']);

    }

    /**
    *@test
    */
    public function it_goes_through_settings_array_recursively_to_find_references_to_files()
    {
        $fileSystem = $this->createVirtualFilesystemForPages([
            'pages' => [
                'about' => [
                    'banner' => [
                        'header.md' => '## header',
                        'subheader.md' => '### subheader',
                        'otherField.md' => '#### something'
                    ]
                ]
            ]
        ]);
        $settingsContent = [
            'banner' => [
                'text' => [
                    'header' => 'file:banner/header.md',
                    'subHeader' => 'file:banner/subheader.md'
                ],
                'someOtherField' => 'file:banner/otherField.md'
            ]
        ];
        $settingsFilePath = '/pages/about';
        $settingsRefHandler = new SettingsReferencesHandler($fileSystem);

        $settingsWithParsedFileReferences = $settingsRefHandler->handleFileReferences($settingsContent, $settingsFilePath);

        $this->assertInstanceOf(File::class, $settingsWithParsedFileReferences['banner']['text']['header']);
        $this->assertInstanceOf(File::class, $settingsWithParsedFileReferences['banner']['text']['subHeader']);
        $this->assertInstanceOf(File::class, $settingsWithParsedFileReferences['banner']['someOtherField']);
    }

    /**
    *@test
    */
    public function it_goes_through_settings_array_to_find_references_to_directories()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $settingsContent = [
            'header' => ' Test header',
            'sections' => ' dir:sections'
        ];
        $settingsFilePath = '/pages/about';
        $settingsRefHandler = new SettingsReferencesHandler($fileSystem);

        $settingsWithParsedDirReferences = $settingsRefHandler->handleDirectoryReferences($settingsContent, $settingsFilePath);

        $this->assertArrayHasKey('test1', $settingsWithParsedDirReferences['sections']);
        $this->assertArrayHasKey('test2', $settingsWithParsedDirReferences['sections']);
        $this->assertInstanceOf(File::class, $settingsWithParsedDirReferences['sections']['test1']);
        $this->assertInstanceOf(File::class, $settingsWithParsedDirReferences['sections']['test2']);
    }

    /**
    *@test
    */
    public function it_goes_through_settings_array_recursively_to_find_references_to_directories()
    {
        $fileSystem = $this->createVirtualFilesystemForPages([
            'pages' => [
                'about' => [
                    'sections' => [
                        'banner' => [
                            'header.md' => '## header',
                            'subheader.md' => '### subheader',
                        ],
                        'section1' => [
                            'header.md' => '## header',
                            'column1.md' => 'something something dark side',
                            'column2.jpg' => 'image'
                        ]
                    ]
                ]
            ]
        ]);
        $settingsContent = [
            'sections' => [
                'banner' => 'dir:sections/banner',
                'section1' => 'dir:sections/section1'
            ]
        ];
        $settingsFilePath = '/pages/about';
        $settingsRefHandler = new SettingsReferencesHandler($fileSystem);

        $settingsWithParsedDirReferences = $settingsRefHandler->handleDirectoryReferences($settingsContent, $settingsFilePath);

        $this->assertArrayHasKey('header', $settingsWithParsedDirReferences['sections']['banner']);
        $this->assertArrayHasKey('subheader', $settingsWithParsedDirReferences['sections']['banner']);
        $this->assertArrayHasKey('header', $settingsWithParsedDirReferences['sections']['section1']);
        $this->assertArrayHasKey('column1', $settingsWithParsedDirReferences['sections']['section1']);
        $this->assertArrayHasKey('column2', $settingsWithParsedDirReferences['sections']['section1']);
        $this->assertInstanceOf(File::class, $settingsWithParsedDirReferences['sections']['banner']['header']);
        $this->assertInstanceOf(File::class, $settingsWithParsedDirReferences['sections']['banner']['subheader']);
        $this->assertInstanceOf(File::class, $settingsWithParsedDirReferences['sections']['section1']['header']);
        $this->assertInstanceOf(File::class, $settingsWithParsedDirReferences['sections']['section1']['column1']);
        $this->assertInstanceOf(File::class, $settingsWithParsedDirReferences['sections']['section1']['column2']);

    }
    
    /**
    *@test
    */
    public function it_throws_exception_if_a_reference_to_a_file_cannot_be_found()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts([
        ]);
        $settingsContent = [
            'content' => 'file:shouldnotbefound.md'
        ];
        $settingsFilePath = '/posts/Frontend/sass-tricks-you-should-know';
        $settingsRefHandler = new SettingsReferencesHandler($fileSystem);

        try{
            $settingsWithParsedFileReferences = $settingsRefHandler->handleFileReferences($settingsContent, $settingsFilePath);
        }catch(FileReferencedInSettingsNotFoundException $e)
        {
            $this->assertEquals("Could not find shouldnotbefound.md file referenced in settings for /posts/Frontend/sass-tricks-you-should-know", $e->getMessage());
            return;
        }

        $this->fail('An exception for missing file, was not thrown');
    }
    
    /**
    *@test
    */
    public function it_throws_exception_if_a_reference_to_a_directory_cannot_be_found()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $settingsContent = [
            'header' => ' Test header',
            'sections' => ' dir:shouldnotbefound'
        ];
        $settingsFilePath = '/pages/about';
        $settingsRefHandler = new SettingsReferencesHandler($fileSystem);

        try{
            $settingsWithParsedDirReferences = $settingsRefHandler->handleDirectoryReferences($settingsContent, $settingsFilePath);
        }catch (DirectoryReferenceInSettingsNotFoundException $e)
        {
            $this->assertEquals('Could not find shouldnotbefound directory referenced in settings for /pages/about', $e->getMessage());
            return;
        }

        $this->fail('An exception for missing directory, was not thrown');
    }


}