<?php
namespace WackyStudio\Flatblog\Builders;

use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;

class PostsBuilder
{

    /**
     * @var array
     */
    private $rawrawEntities;
    /**
     * @var PostEntityFactory
     */
    private $postEntityFactory;

    public function __construct(array $rawEntities, PostEntityFactory $postEntityFactory)
    {
        $this->rawrawEntities = $rawEntities;
        $this->postEntityFactory = $postEntityFactory;
    }

    public function buildSinglePosts()
    {
        $posts = collect($this->rawrawEntities)->transform(function (RawEntity $rawEntity) {
            return $this->postEntityFactory->make($rawEntity);
        });

        var_dump($posts);
    }

}