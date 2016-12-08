<?php
namespace WackyStudio\Flatblog\Parsers;

use WackyStudio\Flatblog\Contracts\ContentParserContract;

class FakeAdditionalParser implements ContentParserContract
{

    /**
     * @param $fileContent
     *
     * @return mixed
     */
    public function parse($fileContent)
    {
        return str_replace('faked', 'faked again', $fileContent);
    }
}