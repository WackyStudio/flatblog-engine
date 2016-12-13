<?php
use League\Flysystem\Filesystem;
use League\Flysystem\Vfs\VfsAdapter;
use Silly\Edition\Pimple\Application;
use WackyStudio\Flatblog\Core\ApplicationFactory;
use VirtualFileSystem\FileSystem as Vfs;

class TestCase extends PHPUnit_Framework_TestCase
{

    /** @var  Application */
    protected $app;

    protected $vfs = null;

    public function setUp()
    {
        parent::setUp();

        $this->app = (new ApplicationFactory(require(__DIR__ . '/../src/dependencies.php')))->boot();

    }

    public function createVirtualFilesystemForPosts($structure = null)
    {
        $this->vfs = new Vfs();

        if($structure == null)
        {
            $this->vfs->createStructure([
                'posts' => [
                    'Frontend' => [
                        'sass-tricks-you-should-know' => [
                            'settings.yml' => implode(PHP_EOL, [
                                'title: Sass tricks you should know!',
                                'summary: This is a summary',
                                'image: file:someimage.jpg',
                                'content: file:content.md',
                                'date: "2016-03-01"'
                            ]),
                            'content.md' => '## Hello World',
                            'someimage.jpg' => 'image'
                        ]
                    ],
                    'Backend' => [
                        'do-you-really-need-a-backend-for-that' => [
                            'settings.yml' => implode(PHP_EOL, [
                                'title: Do you really need a backend for that?',
                                'summary: This is a summary',
                                'image: file:someimage.jpg',
                                'content: file:content.md',
                                'date: "2016-01-02"'
                            ]),
                            'content.md' => '## Hello World 2',
                            'someimage.jpg' => 'image'
                        ]
                    ]
                ],
                'config.yml' => implode(PHP_EOL, [
                    'posts:',
                    '   prefix: blog'
                ]),
            ]);
        } else {
            $this->vfs->createStructure($structure);
        }

        $vfsAdapter = new VfsAdapter($this->vfs);
        return new Filesystem($vfsAdapter);
    }

    public function createVirtualFilesystemForPages($structure = null)
    {
        $this->vfs = new Vfs();

        if($structure == null)
        {
            $this->vfs->createStructure([
                'pages' => [
                   'about' => [
                       'settings.yml' => implode(PHP_EOL, [
                          'header: Test header',
                          'sections: dir:sections'
                       ]),
                       'sections' => [
                           'test1.md' => '## test1',
                           'test2.md' => '## test2',
                       ],
                       'content.md' => '## test'
                   ],
                   'parent' =>[
                       'settings.yml' => 'test: test',
                       'child' => [
                           'settings.yml'=> 'test: test',
                           'skip' => [],
                           'second-child' =>[
                               'settings.yml'=>'test: second'
                           ]
                       ]
                   ]
                ],
                'config.yml' => implode(PHP_EOL, [
                    'posts:',
                    '   prefix: blog',
                    '   templates:',
                    '       single-post: single-post',
                    '       '
                ]),
            ]);
        } else {
            $this->vfs->createStructure($structure);
        }

        $vfsAdapter = new VfsAdapter($this->vfs);
        return new Filesystem($vfsAdapter);
    }



}