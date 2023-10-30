<?php
namespace WackyStudio\Flatblog\Builders;

use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Contracts\BuilderContract;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Entities\PageEntity;
use WackyStudio\Flatblog\Entities\PostEntity;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Factories\PageEntityFactory;
use WackyStudio\Flatblog\Factories\PostEntityFactory;

class SitemapBuilder implements BuilderContract
{

    /**
     * @var array
     */
    private $rawPostsEntities;
    /**
     * @var array
     */
    private $rawPagesEntities;
    /**
     * @var PostEntityFactory
     */
    private $postEntityFactory;
    /**
     * @var PageEntityFactory
     */
    private $pageEntityFactory;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(array $rawPostsEntities, array $rawPagesEntities, PostEntityFactory $postEntityFactory, PageEntityFactory $pageEntityFactory, Config $config, Filesystem $filesystem)
    {
        $this->rawPostsEntities = $rawPostsEntities;
        $this->rawPagesEntities = $rawPagesEntities;
        $this->postEntityFactory = $postEntityFactory;
        $this->pageEntityFactory = $pageEntityFactory;
        $this->config = $config;
        $this->filesystem = $filesystem;
    }
    /**
     * @inheritdoc
     */
    public function build()
    {
        if($this->config->get('skip-sitemap') === true){
            return;
        }

        $siteMap =  collect([])
            ->merge($this->getSitemapForPosts())
            ->merge($this->getSitemapForPages());

        $this->filesystem->write('build/sitemap.txt', $siteMap->implode(PHP_EOL));
    }

    protected function getSitemapForPosts()
    {
        return collect($this->rawPostsEntities)->map(function (RawEntity $entity) {
            return $this->postEntityFactory->make($entity);
        })->map(function (PostEntity $postEntity) {
            return $this->config->get('site-domain') .'/'. $postEntity->destination();
        })->push($this->config->get('site-domain') . '/' . $this->config->get('posts.prefix'))
          ->push($this->config->get('site-domain') . '/' . $this->config->get('posts.prefix').'/categories');
    }

    protected function getSitemapForPages()
    {
        return collect($this->rawPagesEntities)->map(function (RawEntity $entity) {
            return $this->pageEntityFactory->make($entity);
        })->map(function (PageEntity $pageEntity) {

            if($pageEntity->destination() == $this->config->get('pages.frontpage'))
            {
                return $this->config->get('site-domain');
            }

            return $this->config->get('site-domain') .'/' . $pageEntity->destination();
        });
    }
}