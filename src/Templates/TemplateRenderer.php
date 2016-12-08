<?php
namespace WackyStudio\Flatblog\Templates;

use duncan3dc\Laravel\BladeInstance;

class TemplateRenderer
{

    /**
     * @var BladeInstance
     */
    private $blade;

    public function __construct(BladeInstance $blade)
    {
        $this->blade = $blade;
    }

    public function render($view, array $data)
    {
        return $this->blade->render($view,$data);
    }
}