<?php
namespace WackyStudio\Flatblog\Contracts;

interface ContentParserContract
{

    /**
     * @param $fileContent
     *
     * @return mixed
     */
    public function parse($fileContent);

}