<?php
namespace WackyStudio\Flatblog\Parsers;

use WackyStudio\Flatblog\Contracts\ContentParserContract;

class FakeParser implements ContentParserContract
{

    /**
     * @param $fileContent
     *
     * @return mixed
     */
    public function parse($fileContent)
    {
        return 'faked';
    }
}