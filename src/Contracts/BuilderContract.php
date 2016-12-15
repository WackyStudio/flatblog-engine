<?php
namespace WackyStudio\Flatblog\Contracts;

interface BuilderContract
{

    /**
     * Build content and return array where each
     * key determines file placement and each value
     * will be written to each file
     *
     * @return array
     */
    public function build();
}