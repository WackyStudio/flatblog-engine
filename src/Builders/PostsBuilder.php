<?php
namespace WackyStudio\Flatblog\Builders;

use Illuminate\Support\Collection;
use WackyStudio\Flatblog\Entities\PostEntity;
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

    /**
     * @var Collection
     */
    private $posts;
    /**
     * @var array
     */
    private $templates;

    public function __construct(array $rawEntities, PostEntityFactory $postEntityFactory, TemplateRenderer $renderer, array $templates)
    {
        $this->rawrawEntities = $rawEntities;
        $this->postEntityFactory = $postEntityFactory;
        $this->renderer = $renderer;
        $this->templates = $templates;
    }

    public function buildSinglePosts()
    {
        return $this->getPosts()->flatMap(function (PostEntity $postEntity) {
            return [$postEntity->destination() => $this->renderer->render($this->templates['single'], ['post' => $postEntity])];
        });
    }

    private function getPosts()
    {
        if($this->posts == null)
        {
            $this->posts = collect($this->rawrawEntities)->transform(function (RawEntity $rawEntity) {
                return $this->postEntityFactory->make($rawEntity);
            });
        }

        return $this->posts;
    }

}