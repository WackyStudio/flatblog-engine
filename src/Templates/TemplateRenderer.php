<?php
namespace WackyStudio\Flatblog\Templates;

use Illuminate\Contracts\View\Factory as FactoryContract;

class TemplateRenderer
{

    /**
     * @var FactoryContract
     */
    private $blade;

    public function __construct(FactoryContract $blade)
    {
        $this->blade = $blade;
    }

    public function render($view, array $data)
    {
        return $this->blade->make($view,$data)->render();
    }
}