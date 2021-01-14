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
    public $destination;
    public $alt;
    public $seo_title;
    public $seo_description;
    public $seo_keywords;
    public $fb_url;
    public $header_image;
    public $featured_post;
    public $thumbnail;
    public $relations;
    public $slugTitle;


    public function __construct($title, Chronos $date, $category = null, $categoryLink, $summary, $content, $image, $destination, $alt, $featured_post, $seo_title, $seo_description, $seo_keywords, $fb_url, $header_image, $thumbnail, $relations, $slugTitle)
    {
        $this->title = $title;
        $this->date = $date;
        $this->content = $content;
        $this->image = $image;
        $this->thumbnail = $thumbnail;
        $this->category = $category;
        $this->summary = $summary;
        $this->destination = $destination;
        $this->categoryLink = $categoryLink;
        $this->alt = $alt;
        $this->seo_title = $seo_title;
        $this->seo_description = $seo_description;
        $this->seo_keywords = $seo_keywords;
        $this->fb_url = $fb_url;
        $this->header_image = $header_image;
        $this->featured_post = $featured_post;
        $this->relations = $relations;
        $this->slugTitle = $slugTitle;
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

    /**
     * Get Post relations
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Set Post relations
     * @param array $relations
     */
    public function setRelations(array $relations){
        $this->relations = $relations;
    }


}