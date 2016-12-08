<?php
namespace WackyStudio\Flatblog\Entities;

use Carbon\Carbon;
use WackyStudio\Flatblog\Contracts\BuildDestinationContract;

class PostEntity implements BuildDestinationContract
{

    /**
     * @var string
     */
    private $title;

    /**
     * @var Carbon
     */
    private $date;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @var string
     */
    private $summary;
    /**
     * @var
     */
    private $destination;

    public function __construct($title, Carbon $date, $category = null, $summary, $content, $image, $destination)
    {
        $this->title = $title;
        $this->date = $date;
        $this->content = $content;
        $this->image = $image;
        $this->category = $category;
        $this->summary = $summary;
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return Carbon
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return null|string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
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
}