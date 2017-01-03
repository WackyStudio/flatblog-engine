<?php
namespace WackyStudio\Flatblog\Commands;

use Symfony\Component\Console\Output\OutputInterface;
use WackyStudio\Flatblog\Makers\PageMaker;

class CreatePage
{
    public function __invoke($name, $parent, $template,  OutputInterface $output, PageMaker $pageMaker)
    {
        $pageMaker->makePageWithName($name, $parent, $template);

        $output->writeln("Created Page: {$name}");
    }
}