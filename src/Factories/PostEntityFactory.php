<?php
namespace WackyStudio\Flatblog\Factories;

use Carbon\Carbon;
use WackyStudio\Flatblog\Entities\PostEntity;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Exceptions\InvalidDateGivenInSettingsFileException;
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
            $this->handlePostDate($rawEntity, $settings),
            $rawEntity->getSubDirectories()[1],
            $settings['summary'],
            $settings['content'],
            $settings['image'],
            strtolower($rawEntity->getPath())
        );
    }

    /**
     * @param RawEntity $rawEntity
     * @param $settings
     *
     * @return mixed
     */
    protected function handlePostDate(RawEntity $rawEntity, $settings)
    {
        if ( ! isset($settings['date'])) {
            return $rawEntity->getDateTime();
        } else {
            try {
                return Carbon::parse($settings['date']);
            } catch (\Exception $e) {
                throw new InvalidDateGivenInSettingsFileException("Invalid date given in settings file for {$rawEntity->getPath()}");
            }
        }
    }
}