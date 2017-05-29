<?php
namespace WackyStudio\Flatblog\Makers;

use Cake\Chronos\Chronos;
use Illuminate\Support\Str;
use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use WackyStudio\Flatblog\Exceptions\CannotFindPostsException;

class PostMaker
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function makeNewPostNamed($name, $category = null)
    {
        if(!$this->filesystem->has('posts'))
        {
            throw new CannotFindPostsException('Cannot find posts folder in current location');
        }

        $path = collect(['posts']);

        if(!is_null($category))
        {
           $path->push(Str::slug($category));
        }

        $path->push(Str::slug($name));
        $path = $path->implode('/');

        $this->filesystem->write($path . '/settings.yml', Yaml::dump([
            'title' => $name,
            'summary' => 'Create your post summary here...',
            'image' => 'Give a path to your post image here, like images/image.jpg',
            'content' => 'file:content.md',
            'date' => Chronos::now()->format('Y-m-d')
        ]));

        $this->filesystem->write($path . '/content.md', 'Post Contents');
    }
}