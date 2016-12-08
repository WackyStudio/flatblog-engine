<?php
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\PostsBuilder;
use WackyStudio\Flatblog\Commands\Build;
use WackyStudio\Flatblog\Commands\CreatePage;
use WackyStudio\Flatblog\Commands\CreatePost;
use WackyStudio\Flatblog\Commands\NewProject;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\Parsers\MarkdownContentParser;
use WackyStudio\Flatblog\Parsers\ParserManager;
use WackyStudio\Flatblog\Parsers\SettingsParser;

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
    // App Dependencies
    Filesystem::class => function($container){
      return new Filesystem($container['adapter']);
    },

    // Factories
    RawEntityFactory::class => function($container){
      return new RawEntityFactory($container[Filesystem::class], $container[SettingsParser::class]);
    },
    PostEntityFactory::class => function($container){
        return new PostEntityFactory($container[ParserManager::class]);
    },

    // Parsers
    ParserManager::class => function($container){
      return new ParserManager($container['contentParsers'], $container[Filesystem::class], $container);
    },
    SettingsParser::class => function($container){
        return new SettingsParser($container[Filesystem::class]);
    },
    MarkdownContentParser::class => function(){
        $parseDownExtra = new \ParsedownExtra();
        return new MarkdownContentParser($parseDownExtra);
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