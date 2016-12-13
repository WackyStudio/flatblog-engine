<?php
namespace WackyStudio\Flatblog\Entities;

use WackyStudio\Flatblog\Contracts\BuildDestinationContract;

class PageEntity implements BuildDestinationContract
{

    /**
     * @var string
     */
    public $template;
    /**
     * @var array
     */
    private $attributes;
    /**
     * @var string
     */
    private $destination;

    public function __construct($template, array $attributes, $destination)
    {
        $this->template = $template;
        $this->attributes = $attributes;
        $this->destination = $destination;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if(array_key_exists($name, $this->attributes))
        {
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * Entity destination when building
     *
     * @return string
     */
    public function destination()
    {
        return $this->destination;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}