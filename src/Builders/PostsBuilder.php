<?php
namespace WackyStudio\Flatblog\Builders;

use Illuminate\Support\Collection;
use WackyStudio\Flatblog\Contracts\BuilderContract;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Entities\PostEntity;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

class PostsBuilder implements BuilderContract
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
    /**
     * @var Config
     */
    private $config;

    public function __construct(array $rawEntities, PostEntityFactory $postEntityFactory, TemplateRenderer $renderer, Config $config)
    {
        $this->rawrawEntities = $rawEntities;
        $this->postEntityFactory = $postEntityFactory;
        $this->renderer = $renderer;
        $this->templates = $config->get('posts.templates');
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function build()
    {
        $toWrite = collect([])
            ->merge($this->buildSinglePosts())
            ->merge($this->buildPostsList())
            ->merge($this->buildSingleCategories())
            ->merge($this->buildCategoryList());

        return $toWrite;
    }

    public function buildSinglePosts()
    {
        return $this->getPosts()->flatMap(function (PostEntity $postEntity) {
            return [$postEntity->destination() => $this->renderer->render($this->templates['single'], ['post' => $postEntity])];
        });
    }

    public function buildPostsList()
    {
        $posts = $this->getPosts()->sortByDesc(function (PostEntity $postEntity) {
            return $postEntity->date->toDateString();
        });

        $prefix = ($this->config->get('posts.prefix') !== null) ? $this->config->get('posts.prefix') : 'posts';

        return [$prefix => $this->renderer->render($this->templates['all-posts'], ['posts'=>$posts])];
    }

    public function buildSingleCategories()
    {
        $categories = $this->getPosts()->groupBy(function (PostEntity $postEntity) {
            return $postEntity->category;
        })->sort()->transform(function ($item, $key) {
            $item->sortByDesc(function (PostEntity $postEntity) {
                return $postEntity->date->toDateString();
            });
            return $this->renderer->render($this->templates['single-category'], ['category' => $key, 'posts'=>$item]);
        })->flatMap(function($posts, $categoryName){
            $prefix = ($this->config->get('posts.prefix') !== null) ? $this->config->get('posts.prefix') : 'posts';
            return [$prefix.'/'.strtolower($categoryName) => $posts];
        });

        return $categories;
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

    public function buildCategoryList()
    {
        $categories = $this->getPosts()->groupBy(function (PostEntity $postEntity) {
            return $postEntity->category;
        })->map(function ($posts, $key) {
            $category = new \stdClass();
            $category->title = $key;
            $category->postsCount = $posts->count();
            return $category;
        });

        $prefix = ($this->config->get('posts.prefix') !== null) ? $this->config->get('posts.prefix') : 'posts';

        return [$prefix.'/categories' => $this->renderer->render($this->templates['all-categories'], ['categories'=>$categories])];
    }

}