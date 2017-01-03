<?php
namespace WackyStudio\Flatblog\Makers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use WackyStudio\Flatblog\Exceptions\CannotFindPagesException;
use WackyStudio\Flatblog\Exceptions\ReferencedParentPageCannotBeFoundException;

class PageMaker
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function makePageWithName($name, $parentPage = null, $template = null)
    {
        if(!$this->filesystem->has('pages'))
        {
            throw new CannotFindPagesException('Cannot find pages folder in current location');
        }

        $path = (!is_null($parentPage)) ? $this->getPathToParentPage($parentPage) : collect(['pages']);

        $path->push(Str::slug($name));
        $path = $path->implode('/');

        $this->filesystem->write($path . '/settings.yml', Yaml::dump([
            'template' => (!is_null($template)) ? $template : 'the name of your template'
        ]));
    }

    /**
     * @param $parentPage
     *
     * @return Collection
     */
    protected function getPathToParentPage($parentPage)
    {
        $path = collect($this->filesystem->listContents('pages', true))
            ->filter(function ($item) use ($parentPage) {
                return $item['basename'] == $parentPage;
            })
            ->values()
            ->flatten(1)
            ->only('path')
            ->flatten();

        if($path->count() == 0)
        {
            throw new ReferencedParentPageCannotBeFoundException("Cannot find referenced parent page with name of: {$parentPage}");
        }

        return $path;
    }
}