<?php
namespace WackyStudio\Flatblog\Entities;

use Carbon\Carbon;
use WackyStudio\Flatblog\Contracts\BuildDestinationContract;

class PostEntity implements BuildDestinationContract
{

    /**
     * @var string
     */
    public $title;

    /**
     * @var Carbon
     */
    public $date;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $image;

    /**
     * @var string|null
     */
    public $category;

    /**
     * @var string
     */
    public $summary;

    /**
     * @var string
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
     * Entity destination when building
     *
     * @return string
     */
    public function destination()
    {
       return $this->destination;
    }


}