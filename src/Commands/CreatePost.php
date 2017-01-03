<?php
namespace WackyStudio\Flatblog\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WackyStudio\Flatblog\Makers\PostMaker;

class CreatePost
{
    public function __invoke($name, $category = null, OutputInterface $output, PostMaker $postMaker)
    {
        // Take Name and Category
            // Send these into Post Maker
                // check if posts directory exists
                // Slugify name and category
                // Place a settings.yml stub inside slugified path
        $postMaker->makeNewPostNamed($name, $category);

        $output->writeln("Created Post: {$name}");
    }
}