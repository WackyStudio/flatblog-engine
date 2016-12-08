<?php
namespace WackyStudio\Flatblog\Commands;

use Symfony\Component\Console\Output\OutputInterface;

class CreatePage
{
    public function __invoke($name, OutputInterface $output)
    {
        $output->writeln("{$name}");
    }
}