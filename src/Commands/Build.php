<?php
namespace WackyStudio\Flatblog\Commands;

use Symfony\Component\Console\Output\OutputInterface;

class Build
{

    public function build(OutputInterface $output)
    {
        $output->writeln('Building');
    }

}