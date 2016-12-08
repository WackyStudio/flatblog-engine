#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use WackyStudio\Flatblog\Core\ApplicationFactory;

$app = (new ApplicationFactory(require(__DIR__ . '/src/dependencies.php')))->boot();
require(__DIR__ . '/src/commands.php');
$app->run();