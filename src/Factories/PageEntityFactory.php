<?php
namespace WackyStudio\Flatblog\Factories;

use WackyStudio\Flatblog\Entities\PageEntity;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Exceptions\PageIsMissingTemplateException;

class PageEntityFactory
{
    public function make(RawEntity $rawEntity)
    {
        $settings = $rawEntity->getSettings();

        if(!isset($settings['template']))
        {
            throw new PageIsMissingTemplateException('Page is missing template');
        }

        $template = $settings['template'];
        $attributes = collect($rawEntity->getSettings())->filter(function($item, $key){
            return $key !== 'template';
        })->toArray();


        return new PageEntity($template, $attributes, str_replace('pages/', '', strtolower($rawEntity->getPath())));
    }


}