<?php
namespace WackyStudio\Flatblog\Builders;

use Ausi\SlugGenerator\SlugGenerator;
use Ausi\SlugGenerator\SlugOptions;
use Cocur\Slugify\Slugify;
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

    private $slugify;

    public function __construct(array $rawEntities, PostEntityFactory $postEntityFactory, TemplateRenderer $renderer, Config $config)
    {
        $this->rawrawEntities = $rawEntities;
        $this->postEntityFactory = $postEntityFactory;
        $this->renderer = $renderer;
        $this->templates = $config->get('posts.templates');
        $this->config = $config;

        $this->slugify = new Slugify(['regexp' => '/([^a-z0-9\/]|-)+/']);
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
            ->merge($this->buildListOfAllCategories());

        return $toWrite;
    }

    public function buildSinglePosts()
    {
        $posts = $this->getPosts();

        $posts->transform(function (PostEntity $entity) use ($posts) {

            $relations = collect($entity->relations)->map(function ($relation) use ($posts) {
                $post = $posts->filter(function(PostEntity $postEntity) use($relation){
                    return $postEntity->slugTitle === $relation;
                })->first();


                if($post !== null){
                    return $post;
                }
            });

            $entity->setRelations($relations->toArray());
            return $entity;

        });

        return $posts->flatMap(function (PostEntity $postEntity) {
            return [$postEntity->destination() => $this->renderer->render($this->templates['single'], ['post' => $postEntity])];
        });
    }

    public function buildPostsList()
    {
        $posts = $this->getPosts()->sortByDesc(function (PostEntity $postEntity) {
            return $postEntity->date->toDateString();
        });

        if($posts->count() > $this->config->get('posts.paginate-lists-at') )
        {
            return $this->buildPostsListWithPagination($posts);
        }

        return $this->buildPostListWithoutPagination($posts);
    }

    public function buildSingleCategories()
    {
        $categories = $this->getPosts()->groupBy(function (PostEntity $postEntity) {
            return $postEntity->category;
        })->sort()->transform(function ($item, $key) {
            $item->sortByDesc(function (PostEntity $postEntity) {
                return $postEntity->date->toDateString();
            });
            return $this->renderer->render($this->templates['single-category'], ['category' => $key, 'posts'=>$item, 'categories' => $this->buildCategoryList()]);
        })->flatMap(function($posts, $categoryName){
            $prefix = $this->getPostPrefix();
            return [$prefix.'/'.$this->slugify->slugify($categoryName) => $posts];
        });

        return $categories;
    }

    public function buildListOfAllCategories()
    {
        $categories = $this->buildCategoryList();
        $prefix = $this->getPostPrefix();

        return [$prefix.'/categories' => $this->renderer->render($this->templates['all-categories'], ['categories'=>$categories])];
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

    /**
     * @param $posts
     *
     * @return array
     */
    protected function buildPostListWithoutPagination(Collection $posts)
    {
        $prefix = $this->getPostPrefix();

        return [$prefix => $this->renderer->render($this->templates['all-posts'], ['posts' => $posts, 'categories' => $this->buildCategoryList()])];
    }

    /**
     * @param $posts
     *
     * @return array
     */
    private function buildPostsListWithPagination(Collection $posts)
    {
        $prefix = $this->getPostPrefix();
        $postsPaginated = $posts->chunk($this->config->get('posts.paginate-lists-at'));

        $totalPages = $postsPaginated->count();

        return $postsPaginated->flatMap(function ($posts, $key) use($prefix, $totalPages){
            $pageNumber = $key + 1;

            if($pageNumber == 1)
            {
                return [$prefix => $this->renderer->render($this->templates['all-posts'], ['posts' => $posts, 'currentPage' => $pageNumber, 'totalPages' => $totalPages, 'categories' => $this->buildCategoryList()])];
            }

            return  [$prefix."/page/{$pageNumber}" => $this->renderer->render($this->templates['all-posts'], ['posts' => $posts, 'currentPage' => $pageNumber, 'totalPages' => $totalPages])];
        })->toArray();
    }

    /**
     * @return string
     */
    protected function getPostPrefix()
    {
        $prefix = ( $this->config->get('posts.prefix') !== null ) ? $this->config->get('posts.prefix') : 'posts';

        return $prefix;
    }

    /**
     * @return array
     */
    protected function buildCategoryList()
    {
        $categories = $this->getPosts()
                           ->groupBy(function (PostEntity $postEntity) {
                               return $postEntity->category;
                           })
                           ->flatMap(function ($posts, $key) {
                               $category = new \stdClass();
                               $category->title = $key;
                               $category->postsCount = $posts->count();

                               return [$this->slugify->slugify($key) => $category];
                           })->toArray();
        return $categories;
    }

}