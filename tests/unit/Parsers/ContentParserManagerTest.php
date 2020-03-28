<?php
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Parsers\ContentParserManager;
use WackyStudio\Flatblog\Parsers\FakeAdditionalParser;
use WackyStudio\Flatblog\Parsers\FakeParser;

class ContentParserManagerTest extends TestCase
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
    public function it_goes_through_settings_array_and_send_files_through_content_parsers()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $contentParsers = [
            'md' => FakeParser::class
        ];
        $this->app->getContainer()[FakeParser::class] = function () {
            return new FakeParser();
        };
        $settingsFile = [
            'title'   => ' Do you really need a backend for that?',
            'summary' => 'This is a summary',
            'image'   =>  new File('posts/Back end/do-you-really-need-a-backend-for-that', time(), 100, 'Backend', 'someimage.jpg', 'jpg',
                'someimage'),
            'content' =>  new File('posts/Back end/do-you-really-need-a-backend-for-that', time(), 100, 'Backend', 'content.md', 'md',
                'content'),
        ];

        $contentParser = new ContentParserManager($contentParsers, $fileSystem, $this->app->getContainer());
        $parsedSettings = $contentParser->parseContentFromSettings($settingsFile);
        $this->assertArrayHasKey('content', $parsedSettings);
        $this->assertEquals($parsedSettings['content'], 'faked');
    }

    /**
     *@test
     */
    public function it_supports_multiple_parsers_for_same_file_extension()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $contentParsers = [
            'md' => [
                FakeParser::class,
                FakeAdditionalParser::class
            ]
        ];

        $this->app->getContainer()[FakeParser::class] = function(){
            return new FakeParser();
        };

        $this->app->getContainer()[FakeAdditionalParser::class] = function(){
            return new FakeAdditionalParser();
        };

        $settingsFile = [
            'title'   => ' Do you really need a backend for that?',
            'summary' => 'This is a summary',
            'image'   =>  new File('posts/Back end/do-you-really-need-a-backend-for-that', time(), 100, 'Backend', 'someimage.jpg', 'jpg',
                'someimage'),
            'content' =>  new File('posts/Back end/do-you-really-need-a-backend-for-that', time(), 100, 'Backend', 'content.md', 'md',
                'content'),
        ];

        $contentParser = new ContentParserManager($contentParsers, $fileSystem, $this->app->getContainer());
        $parsedSettings = $contentParser->parseContentFromSettings($settingsFile);

        $this->assertArrayHasKey('content', $parsedSettings);
        $this->assertEquals($parsedSettings['content'], 'faked again');
    }
    
    /**
    *@test
    */
    public function it_goes_through_settings_array_recursively_and_send_files_through_content_parsers()
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
        $settingsFile = [
            'banner' => [
                'text' => [
                    'header' =>  new File('pages/about/banner', time(), 100, 'banner', 'header.md', 'md', 'header'),
                    'subHeader' => new File('pages/about/banner', time(), 100, 'banner', 'subheader.md', 'md', 'subheader'),
                ],
                'someOtherField' => new File('pages/about/banner', time(), 100, 'banner', 'otherField.md', 'md', 'otherField'),
            ]
        ];

        $contentParsers = [
            'md' => [
                FakeParser::class,
            ]
        ];

        $this->app->getContainer()[FakeParser::class] = function(){
            return new FakeParser();
        };

        $contentParser = new ContentParserManager($contentParsers, $fileSystem, $this->app->getContainer());
        $parsedSettings = $contentParser->parseContentFromSettings($settingsFile);

        $this->assertArrayHasKey('header', $parsedSettings['banner']['text']);
        $this->assertArrayHasKey('subHeader', $parsedSettings['banner']['text']);
        $this->assertArrayHasKey('someOtherField', $parsedSettings['banner']);
        $this->assertEquals($parsedSettings['banner']['text']['header'], 'faked');
        $this->assertEquals($parsedSettings['banner']['text']['subHeader'], 'faked');
        $this->assertEquals($parsedSettings['banner']['someOtherField'], 'faked');

    }

}