<?php
namespace WackyStudio\Flatblog\Builders;

class Builder
{
    private $buildDirectories = [
        'pages',
        'posts'
    ];

    private $builders = [
        'pages' => PagesBuilder::class,
        'posts' => PostsBuilder::class
    ];


}