<?php
namespace WackyStudio\Flatblog\Builders;

use Illuminate\Support\Collection;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Entities\PageEntity;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Factories\PageEntityFactory;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

class PagesBuilder
{

    /**
     * @var array
     */
    private $rawEntities;
    /**
     * @var PageEntityFactory
     */
    private $pageEntityFactory;
    /**
     * @var TemplateRenderer
     */
    private $renderer;
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Collection
     */
    private $pages;

    public function __construct(array $rawEntities, PageEntityFactory $pageEntityFactory, TemplateRenderer $renderer, Config $config)
    {
        $this->rawEntities = $rawEntities;
        $this->pageEntityFactory = $pageEntityFactory;
        $this->renderer = $renderer;
        $this->config = $config;
    }

    public function build()
    {
        return $this->getPages()->flatMap(function (PageEntity $pageEntity) {
            return [$pageEntity->destination() => $this->renderer->render($pageEntity->template, $pageEntity->getAttributes())];
        });
    }

    private function getPages()
    {
        if($this->pages === null)
        {
            $this->pages = collect($this->rawEntities)->transform(function (RawEntity $rawEntity) {
               return  $this->pageEntityFactory->make($rawEntity);
            });
        }

        return $this->pages;
    }
}