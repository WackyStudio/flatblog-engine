<?php
namespace WackyStudio\Flatblog\Parsers;

use WackyStudio\Flatblog\Contracts\ContentParserContract;

class MarkdownContentParser implements ContentParserContract
{

    /**
     * @var \ParsedownExtra
     */
    private $parsedownExtra;

    public function __construct(\ParsedownExtra $parsedownExtra)
    {
        $this->parsedownExtra = $parsedownExtra;
    }

    /**
     * @param $fileContent
     *
     * @return mixed
     */
    public function parse($fileContent)
    {
        return $this->parsedownExtra->parse($fileContent);
    }
}