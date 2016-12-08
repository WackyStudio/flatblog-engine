<?php

$app->command('build', [WackyStudio\Flatblog\Commands\Build::class, 'build'])->descriptions('Build Project');
$app->command('create:page name', WackyStudio\Flatblog\Commands\CreatePage::class)->descriptions('Create a new page');
$app->command('create:post [--category=] name', WackyStudio\Flatblog\Commands\CreatePost::class)->descriptions('Create a new post');
$app->command('new name', WackyStudio\Flatblog\Commands\NewProject::class)->descriptions('Create a new Flatblog Project');