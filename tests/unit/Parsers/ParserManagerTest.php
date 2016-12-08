<?php
use Carbon\Carbon;
use WackyStudio\Flatblog\Contracts\ContentParserContract;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Parsers\FakeAdditionalParser;
use WackyStudio\Flatblog\Parsers\FakeParser;
use WackyStudio\Flatblog\Parsers\ParserManager;

class ParserManagerTest extends TestCase
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
    public function it_parses_given_files_with_compatible_parser()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $contentParsers = [
            'md' => FakeParser::class
        ];

        $this->app->getContainer()[FakeParser::class] = function(){
            return new FakeParser();
        };

        $files = [
            new File('posts/Backend/do-you-really-need-a-backend-for-that', time(), 100, 'Backend', 'content.md', 'md', 'content')
        ];

        $parserManager = new ParserManager($contentParsers, $fileSystem, $this->app->getContainer());
        $parsedFiles = $parserManager->parseFiles($files);

        $this->assertArrayHasKey('content', $parsedFiles);
        $this->assertEquals($parsedFiles['content'], 'faked');

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

        $files = [
            new File('posts/Backend/do-you-really-need-a-backend-for-that', time(), 100, 'Backend', 'content.md', 'md', 'content')
        ];

        $parserManager = new ParserManager($contentParsers, $fileSystem, $this->app->getContainer());
        $parsedFiles = $parserManager->parseFiles($files);

        $this->assertArrayHasKey('content', $parsedFiles);
        $this->assertEquals($parsedFiles['content'], 'faked again');
    }

}