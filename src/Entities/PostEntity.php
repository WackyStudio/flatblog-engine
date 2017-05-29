<?php
namespace WackyStudio\Flatblog\Entities;

use Cake\Chronos\Chronos;
use WackyStudio\Flatblog\Contracts\BuildDestinationContract;

class PostEntity implements BuildDestinationContract
{

    /**
     * @var string
     */
    public $title;

    /**
     * @var Chronos
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
    public $categoryLink;

    /**
     * @var string
     */
    private $destination;


    public function __construct($title, Chronos $date, $category = null, $categoryLink, $summary, $content, $image, $destination)
    {
        $this->title = $title;
        $this->date = $date;
        $this->content = $content;
        $this->image = $image;
        $this->category = $category;
        $this->summary = $summary;
        $this->destination = $destination;
        $this->categoryLink = $categoryLink;
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