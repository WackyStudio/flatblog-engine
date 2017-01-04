<?php
namespace WackyStudio\Flatblog\Commands;

use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WackyStudio\Flatblog\Builders\Builder;

class Build
{

    public function build(OutputInterface $output, Builder $builder)
    {
        $output->writeln('Building...');
        $builder->build();
        $output->writeln('<info>Finished</info>');
    }

}