<?php
namespace WackyStudio\Flatblog\Core;

use Illuminate\Support\Collection;
use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use WackyStudio\Flatblog\Exceptions\NoValueMatchedGivenConfigKeysException;

class Config
{
    static private $instance = null;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Collection
     */
    private $config;

    public static function getInstance(Filesystem $fileSystem)
    {
        if(self::$instance === null)
        {
            self::$instance = new self($fileSystem);
        }

        return self::$instance;
    }

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->parseConfigFile();

    }

    public function all()
    {
        return $this->config->all();
    }

    public function get($keys)
    {
        $explodedKeys = explode('.', $keys);

        $current = $this->config->toArray();
        
        foreach ($explodedKeys as $key)
        {
            if(!array_key_exists($key, $current))
            {
               return null;
            }

            $current = $current[$key];
        }

        
        return $current;
    }

    private function parseConfigFile()
    {
        return $this->config = new Collection(Yaml::parse($this->filesystem->read('config.yml')));
    }

}