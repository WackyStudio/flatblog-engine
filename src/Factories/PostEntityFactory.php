<?php
namespace WackyStudio\Flatblog\Factories;

use WackyStudio\Flatblog\Entities\PostEntity;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Exceptions\PostIsMissingContentException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingImageException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingSummaryException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingTitleException;
use WackyStudio\Flatblog\Parsers\ParserManager;

class PostEntityFactory
{

    public function make(RawEntity $rawEntity)
    {
        $settings = $rawEntity->getSettings();

        if ( ! isset($settings['title'])) {
            throw new PostIsMissingTitleException('Post is missing title');
        }

        if( ! isset($settings['summary']))
        {
            throw new PostIsMissingSummaryException('Post is missing summary');
        }

        if( ! isset($settings['image']))
        {
            throw new PostIsMissingImageException('Post is missing image');
        }

        if( ! isset($settings['content']))
        {
            throw new PostIsMissingContentException('Post is missing content');
        }

        return new PostEntity(
            $settings['title'],
            $rawEntity->getDateTime(),
            $rawEntity->getSubDirectories()[1],
            $settings['summary'],
            $settings['content'],
            $settings['image'],
            strtolower($rawEntity->getPath())
        );
    }
}