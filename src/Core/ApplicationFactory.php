<?php
namespace WackyStudio\Flatblog\Core;

use Silly\Edition\Pimple\Application;

class ApplicationFactory
{

    /**
     * @var array
     */
    private $dependencies;

    public function __construct(array $dependencies = null)
    {
        $this->dependencies = $dependencies;
    }
    
    /**
     * @return Application
     */
    public function boot()
    {
        $app = new Application();
        $container = $app->getContainer();

        $container = $this->setCurrentWorkingDirectory($container);

        $this->registerDependencies($container);

        return $app;
    }

    /**
     * @param $container
     *
     * @return mixed
     */
    protected function setCurrentWorkingDirectory($container)
    {
        $container['CWD'] = function () {
            return getcwd();
        };

        return $container;
    }

    /**
     * @param $container
     */
    protected function registerDependencies($container)
    {
        if ($this->dependencies !== null) {
            foreach ($this->dependencies as $key => $closure) {
                $container[$key] = $closure;
            }
        }
    }

}