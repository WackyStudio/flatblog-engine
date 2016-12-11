<?php
namespace WackyStudio\Flatblog\Builders;

use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

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
    /**
     * @var TemplateRenderer
     */
    private $renderer;

    public function __construct(array $rawEntities, PostEntityFactory $postEntityFactory, TemplateRenderer $renderer)
    {
        $this->rawrawEntities = $rawEntities;
        $this->postEntityFactory = $postEntityFactory;
        $this->renderer = $renderer;
    }

    public function buildSinglePosts()
    {
        $posts = collect($this->rawrawEntities)->flatMap(function (RawEntity $rawEntity) {
            $postEntity = $this->postEntityFactory->make($rawEntity);
            return [$postEntity->destination() => $this->renderer->render('single.post', ['post' => $postEntity])];
        });

        return $posts;
    }

}