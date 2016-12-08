<?php
namespace WackyStudio\Flatblog\Contracts;

interface BuildDestinationContract
{

    /**
     * Entity destination when building
     *
     * @return string
     */
    public function destination();
}