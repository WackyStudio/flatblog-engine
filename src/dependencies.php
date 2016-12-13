<?php
use duncan3dc\Laravel\BladeInstance;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\PostsBuilder;
use WackyStudio\Flatblog\Commands\Build;
use WackyStudio\Flatblog\Commands\CreatePage;
use WackyStudio\Flatblog\Commands\CreatePost;
use WackyStudio\Flatblog\Commands\NewProject;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\Parsers\ContentParserManager;
use WackyStudio\Flatblog\Parsers\MarkdownContentParser;
use WackyStudio\Flatblog\Settings\SettingsParser;
use WackyStudio\Flatblog\Settings\SettingsReferencesHandler;

return [
    //configs
    'adapter' => function($container){
        return new Local($container['CWD']);
    },

    'contentParsers' => function($container){
        return [
            'md' => MarkdownContentParser::class
        ];
    },

    'config' => function($container){
        return Config::getInstance($container[Filesystem::class]);
    },

    // App Core Dependencies
    Filesystem::class => function($container){
      return new Filesystem($container['adapter']);
    },

    // Factories
    RawEntityFactory::class => function($container){
      return new RawEntityFactory($container[Filesystem::class], $container[SettingsParser::class], $container[ContentParserManager::class]);
    },
    PostEntityFactory::class => function($container){
        return new PostEntityFactory;
    },

    // Settings
    SettingsParser::class => function($container){
        return new SettingsParser($container[Filesystem::class], $container[SettingsReferencesHandler::class]);
    },
    SettingsReferencesHandler::class => function($container){
        return new SettingsReferencesHandler($container[Filesystem::class]);
    },

    // Parsers
    ContentParserManager::class => function($container){
      return new ContentParserManager($container['contentParsers'], $container[Filesystem::class], $container);
    },

    MarkdownContentParser::class => function(){
        $parseDownExtra = new \ParsedownExtra();
        return new MarkdownContentParser($parseDownExtra);
    },

    // Templates
    BladeInstance::class => function($container){
        $viewPath = $container['CWD'] . '/resources/views';
        $cachePath = $container['CWD'] . '/temp';
        return new BladeInstance($viewPath, $cachePath);
    },


    // Commands
    Build::class => function(){
        return new Build;
    },
    CreatePage::class => function(){
        return new CreatePage;
    },
    CreatePost::class => function(){
        return new CreatePost;
    },
    NewProject::class => function(){
        return new NewProject;
    }

];