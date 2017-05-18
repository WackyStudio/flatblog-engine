<?php
use duncan3dc\Laravel\BladeInstance;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\Builder;
use WackyStudio\Flatblog\Builders\ImageFolderBuilder;
use WackyStudio\Flatblog\Builders\MediaFolderBuilder;
use WackyStudio\Flatblog\Builders\PagesBuilder;
use WackyStudio\Flatblog\Builders\PostsBuilder;
use WackyStudio\Flatblog\Builders\SitemapBuilder;
use WackyStudio\Flatblog\Commands\Build;
use WackyStudio\Flatblog\Commands\CreatePage;
use WackyStudio\Flatblog\Commands\CreatePost;
use WackyStudio\Flatblog\Commands\NewProject;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Factories\PageEntityFactory;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\FileWriters\BuildFileWriter;
use WackyStudio\Flatblog\Makers\PageMaker;
use WackyStudio\Flatblog\Makers\PostMaker;
use WackyStudio\Flatblog\Parsers\ContentParserManager;
use WackyStudio\Flatblog\Parsers\MarkdownContentParser;
use WackyStudio\Flatblog\Settings\SettingsParser;
use WackyStudio\Flatblog\Settings\SettingsReferencesHandler;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

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
    BuildFileWriter::class => function($container){
        return new BuildFileWriter($container[Filesystem::class]);
    },

    // Builders
    Builder::class => function($container){
        return new Builder($container[BuildFileWriter::class], $container);
    },
    PostsBuilder::class => function($container){
        $rawEntities = ($container[RawEntityFactory::class])->getEntitiesForDirectory('posts');
        return new PostsBuilder($rawEntities, $container[PostEntityFactory::class], $container[TemplateRenderer::class], $container['config']);
    },
    PagesBuilder::class => function($container){
        $rawEntities = ($container[RawEntityFactory::class])->getEntitiesForDirectory('pages');
        return new PagesBuilder($rawEntities, $container[PageEntityFactory::class], $container[TemplateRenderer::class], $container['config']);
    },
    ImageFolderBuilder::class => function($container){
        $rawEntities = collect([])
            ->merge(($container[RawEntityFactory::class])->getEntitiesForDirectory('posts'))
            ->merge(($container[RawEntityFactory::class])->getEntitiesForDirectory('pages'))
            ->toArray();
        return new ImageFolderBuilder($rawEntities, $container[Filesystem::class]);
    },
    SitemapBuilder::class => function($container){
        $rawPostsEntities = ($container[RawEntityFactory::class])->getEntitiesForDirectory('posts');
        $rawPagesEntities = ($container[RawEntityFactory::class])->getEntitiesForDirectory('pages');
        return new SitemapBuilder($rawPostsEntities, $rawPagesEntities, $container[PostEntityFactory::class], $container[PageEntityFactory::class], $container['config'],
            $container[Filesystem::class]);
    },
    MediaFolderBuilder::class => function($container){
        return new MediaFolderBuilder($container[Filesystem::class]);
    },

    // Factories
    RawEntityFactory::class => function($container){
      return new RawEntityFactory($container[Filesystem::class], $container[SettingsParser::class], $container[ContentParserManager::class]);
    },
    PostEntityFactory::class => function($container){
        return new PostEntityFactory($container['config']);
    },
    PageEntityFactory::class => function($container){
        return new PageEntityFactory;
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
    TemplateRenderer::class => function($container){
        return new TemplateRenderer($container[BladeInstance::class], $container['config']);
    },
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
    },

    // File Makers
    PostMaker::class => function($container){
        return new PostMaker($container[Filesystem::class]);
    },
    PageMaker::class => function($container){
        return new PageMaker($container[Filesystem::class]);
    }

];