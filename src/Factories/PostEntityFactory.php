<?php
namespace WackyStudio\Flatblog\Factories;

use Ausi\SlugGenerator\SlugGenerator;
use Ausi\SlugGenerator\SlugOptions;
use Cake\Chronos\Chronos;
use Cocur\Slugify\Slugify;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Entities\PostEntity;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Exceptions\InvalidDateGivenInSettingsFileException;
use WackyStudio\Flatblog\Exceptions\InvalidRelationArrayGivenInSettingsFileException;
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

    private $slugify;

    public function __construct(Config $config)
    {
        $this->config = $config;

        $this->slugify = new Slugify(['regexp' => '/([^a-z0-9\/]|-)+/']);
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
        if ( ! isset($settings['thumbnail'])) {
            throw new PostIsMissingImageException('Post is missing thumbnail');
        }
        if ( ! isset($settings['content'])) {
            throw new PostIsMissingContentException('Post is missing content');
        }
        if ( ! isset($settings['alt'])) {
            throw new PostIsMissingContentException('Post is missing alt');
        }
        if ( ! isset($settings['featured_post'])) {
            throw new PostIsMissingContentException('Post is missing featured post');
        }
        if ( ! isset($settings['seo_title'])) {
            throw new PostIsMissingContentException('Post is missing seo title');
        }
        if ( ! isset($settings['seo_description'])) {
            throw new PostIsMissingContentException('Post is missing seo description');
        }
        if ( ! isset($settings['seo_keywords'])) {
            throw new PostIsMissingContentException('Post is missing seo keywords');
        }
        if ( ! isset($settings['fb_url'])) {
            throw new PostIsMissingContentException('Post is missing facebook url');
        }
        if ( ! isset($settings['header_image'])) {
            throw new PostIsMissingContentException('Post is missing header image');
        }


        return new PostEntity(
            $settings['title'],
            $this->handlePostDate($rawEntity, $settings),
            ucfirst($rawEntity->getSubDirectories()[1]),
            $this->handlePostDestination($rawEntity->getSubDirectories()[0].'/'.$rawEntity->getSubDirectories()[1]),
            $settings['summary'],
            $settings['content'],
            $settings['image'],
            $this->handlePostDestination($this->slugify->slugify($rawEntity->getPath())),
            $settings['alt'],
            $settings['featured_post'],
            $settings['seo_title'],
            $settings['seo_description'],
            $settings['seo_keywords'],
            $settings['fb_url'],
            $settings['header_image'],
            $settings['thumbnail'],
            $this->handlePostRelations($rawEntity, $settings),
            array_last($rawEntity->getSubDirectories())
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
                return Chronos::parse($settings['date']);
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

    private function handlePostRelations(RawEntity $rawEntity, array $settings)
    {
        if ( ! isset($settings['related'])) {
            return [];
        }else{
            if(!is_array($settings['related'])){
                throw new InvalidRelationArrayGivenInSettingsFileException("Invalid relations array given in settings file for {$rawEntity->getPath()}");
            }

            return $settings['related'];
        }


    }
}