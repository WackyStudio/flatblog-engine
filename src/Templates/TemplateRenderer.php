<?php
namespace WackyStudio\Flatblog\Templates;

use Illuminate\Contracts\View\Factory as FactoryContract;
use WackyStudio\Flatblog\Core\Config;

class TemplateRenderer
{

    /**
     * @var FactoryContract
     */
    private $blade;
    /**
     * @var Config
     */
    private $config;

    public function __construct(FactoryContract $blade, Config $config)
    {
        $this->blade = $blade;
        $this->config = $config;
    }

    public function render($view, array $data)
    {
        $data = $this->mixDataWithGlobalConfig($data);

        return $this->blade->make($view, $data)
                           ->render();
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function mixDataWithGlobalConfig(array $data)
    {
        $data = collect($data)
            ->put('config', $this->config)
            ->toArray();
        return $data;
    }
}