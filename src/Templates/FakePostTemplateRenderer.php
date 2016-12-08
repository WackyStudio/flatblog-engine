<?php
namespace WackyStudio\Flatblog\Templates;

use Illuminate\Contracts\View\Factory;

class FakePostTemplateRenderer implements Factory
{

    private $content;

    /**
     * Determine if a given view exists.
     *
     * @param  string $view
     *
     * @return bool
     */
    public function exists($view)
    {
        // TODO: Implement exists() method.
    }

    /**
     * Get the evaluated view contents for the given path.
     *
     * @param  string $path
     * @param  array $data
     * @param  array $mergeData
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function file($path, $data = [], $mergeData = [])
    {
        // TODO: Implement file() method.
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function make($view, $data = [], $mergeData = [])
    {
        $this->content = "<h1>{$data['post']->getTitle()}</h1>";

        return $this;
    }

    /**
     * Add a piece of shared data to the environment.
     *
     * @param  array|string $key
     * @param  mixed $value
     *
     * @return mixed
     */
    public function share($key, $value = null)
    {
        // TODO: Implement share() method.
    }

    /**
     * Register a view composer event.
     *
     * @param  array|string $views
     * @param  \Closure|string $callback
     * @param  int|null $priority
     *
     * @return array
     */
    public function composer($views, $callback, $priority = null)
    {
        // TODO: Implement composer() method.
    }

    /**
     * Register a view creator event.
     *
     * @param  array|string $views
     * @param  \Closure|string $callback
     *
     * @return array
     */
    public function creator($views, $callback)
    {
        // TODO: Implement creator() method.
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string $namespace
     * @param  string|array $hints
     *
     * @return void
     */
    public function addNamespace($namespace, $hints)
    {
        // TODO: Implement addNamespace() method.
    }

    public function render()
    {
        return $this->content;
    }
}