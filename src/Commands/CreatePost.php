<?php
namespace WackyStudio\Flatblog\Commands;

use Symfony\Component\Console\Output\OutputInterface;

class CreatePost
{
    public function __invoke($name, OutputInterface $output)
    {
        $output->writeln("{$name}");
    }
}