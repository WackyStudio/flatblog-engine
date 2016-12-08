<?php
use WackyStudio\Flatblog\Parsers\MarkdownContentParser;

class MarkdownContentParserTest extends TestCase
{

    /**
    * @test
    */
    public function it_parses_markdown_content()
    {
        $source = '## test';
        $expected = '<h2>test</h2>';
        $markdownContentParser = $this->app->getContainer()[MarkdownContentParser::class];

        $parsedContent =$markdownContentParser->parse($source);

        $this->assertEquals($expected, $parsedContent);

    }

}