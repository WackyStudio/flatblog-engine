<?php
namespace WackyStudio\Flatblog\Factories;

use Carbon\Carbon;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Entities\PostEntity;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Exceptions\InvalidDateGivenInSettingsFileException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingContentException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingImageException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingSummaryException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingTitleException;

class PostEntityFactory
{

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function make(RawEntity $rawEntity)
    {
        $settings = $rawEntity->getSettings();
        if ( ! isset($settings['title'])) {
            throw new PostIsMissingTitleException('Post is missing title');
        }
        if ( ! isset($settings['summary'])) {
            throw new PostIsMissingSummaryException('Post is missing summary');
        }
        if ( ! isset($settings['image'])) {
            throw new PostIsMissingImageException('Post is missing image');
        }
        if ( ! isset($settings['content'])) {
            throw new PostIsMissingContentException('Post is missing content');
        }

        return new PostEntity(
            $settings['title'],
            $this->handlePostDate($rawEntity, $settings),
            ucfirst($rawEntity->getSubDirectories()[1]),
            $this->handlePostDestination($rawEntity->getSubDirectories()[0].'/'.$rawEntity->getSubDirectories()[1]),
            $settings['summary'],
            $settings['content'],
            $settings['image'],
            $this->handlePostDestination($rawEntity->getPath())
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

    private function handlePostDestination($path){

        $basePath = str_replace('posts/', '', strtolower($path));

        if($prefix =$this->config->get('posts.prefix'))
        {
            $basePath = "{$prefix}/{$basePath}";
        }

        return $basePath;
    }
}