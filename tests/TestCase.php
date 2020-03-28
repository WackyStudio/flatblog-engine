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
                    'Front end' => [
                        'sass-tricks-you-should-know' => [
                            'settings.yml' => implode(PHP_EOL, [
                                'title: Sass tricks you should know!',
                                'summary: This is a summary',
                                'image: file:someimage.jpg',
                                'content: file:content.md',
                                'date: "2016-03-01"',

                                'alt: this is the alt text',
                                'featured_post: true',
                                'seo_title: SEO Title',
                                'seo_description: SEO Description',
                                'seo_keywords: keywords',
                                'fb_url: facebook url',
                                'header_image: /images/something.jpg',
                            ]),
                            'content.md' => '## Hello World',
                            'someimage.jpg' => 'image'
                        ]
                    ],
                    'Back end' => [
                        'do-you-really-need-a-backend-for-that' => [
                            'settings.yml' => implode(PHP_EOL, [
                                'title: Do you really need a backend for that?',
                                'summary: This is a summary',
                                'image: file:someimage.jpg',
                                'content: file:content.md',
                                'date: "2016-01-02"',

                                'alt: this is the alt text',
                                'featured_post: true',
                                'seo_title: SEO Title',
                                'seo_description: SEO Description',
                                'seo_keywords: keywords',
                                'fb_url: facebook url',
                                'header_image: /images/something.jpg',
                            ]),
                            'content.md' => '## Hello World 2',
                            'someimage.jpg' => 'image'
                        ]
                    ]
                ],
                'config.yml' => implode(PHP_EOL, [
                    "site-domain: http://flatblog.dev",
                    'posts:',
                    '   prefix: blog',
                    '   templates:',
                    '       single: single-post',
                    '       all-posts: all-posts',
                    '       single-category: single-category',
                    '       all-categories: all-categories',
                    '   disqus:',
                    '       shortname: YOUR_SHORT_NAME',
                    '   paginate-lists-at: 5'
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
                            'template: test-page',
                            'header: Test header',
                            'sections: dir:sections',
                            'content: file:content.md'
                       ]),
                       'sections' => [
                           'test1.md' => '## test1',
                           'test2.md' => '## test2',
                       ],
                       'content.md' => '## test'
                   ],
                   'parent' =>[
                       'settings.yml' => implode(PHP_EOL, [
                           'template: test-page',
                           'header: Test header',
                           'test: test',
                       ]),
                       'child' => [
                           'settings.yml'=> implode(PHP_EOL, [
                               'template: test-page',
                               'header: Test header',
                               'test: test',
                           ]),
                           'skip' => [],
                           'second-child' =>[
                               'settings.yml'=>implode(PHP_EOL, [
                                   'template: test-page',
                                   'header: Test header',
                                   'test: second',
                               ])
                           ]
                       ]
                   ]
                ],
                'config.yml' => implode(PHP_EOL, [
                    "site-domain: http://flatblog.dev",
                    'posts:',
                    '   prefix: blog',
                    '   templates:',
                    '       single-post: single-post',
                    '   disqus:',
                    '       shortname: YOUR_SHORT_NAME',
                    '   paginate-lists-at: 5',
                    'pages:',
                    '   frontpage: parent'
                ]
                ),
            ]);
        } else {
            $this->vfs->createStructure($structure);
        }

        $vfsAdapter = new VfsAdapter($this->vfs);
        return new Filesystem($vfsAdapter);
    }

    public function createVirtualFilesystemForPagesAndPosts()
    {
        $this->vfs = new Vfs();

        $this->vfs->createStructure([
            'pages' => [
                'about' => [
                    'settings.yml' => implode(PHP_EOL, [
                        'template: test-page',
                        'header: Test header',
                        'sections: dir:sections',
                        'content: file:content.md'
                    ]),
                    'sections' => [
                        'test1.md' => '## test1',
                        'test2.md' => '## test2',
                    ],
                    'content.md' => '## test',
                    'images' => [
                        'aboutImage.jpg' => 'image'
                    ]
                ],
                'parent' =>[
                    'settings.yml' => implode(PHP_EOL, [
                        'template: test-page',
                        'header: Test header',
                        'test: test',
                    ]),
                    'images' => [
                        'parentImage.png' => 'image',
                    ],
                    'child' => [
                        'settings.yml'=> implode(PHP_EOL, [
                            'template: test-page',
                            'header: Test header',
                            'test: test',
                        ]),
                        'images' => [
                            'childImage.gif' => 'image',
                        ],
                        'skip' => [],
                        'second-child' =>[
                            'settings.yml'=>implode(PHP_EOL, [
                                'template: test-page',
                                'header: Test header',
                                'test: second',
                            ]),
                            'images' => [
                                'second-child-image.bmp' => 'image'
                            ]
                        ]
                    ]
                ]
            ],
            'posts' => [
                'Frontend' => [
                    'sass-tricks-you-should-know' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Sass tricks you should know!',
                            'summary: This is a summary',
                            'image: file:images/someimage.jpg',
                            'content: file:content.md',
                            'date: "2016-03-01"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: keywords',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',


                        ]),
                        'content.md' => '## Hello World',
                        'images' => [
                            'someimage.jpg' => 'image'
                        ]
                    ]
                ],
                'Backend' => [
                    'do-you-really-need-a-backend-for-that' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Do you really need a backend for that?',
                            'summary: This is a summary',
                            'image: file:images/someimage.jpg',
                            'content: file:content.md',
                            'date: "2016-01-02"'
                        ]),
                        'content.md' => '## Hello World 2',
                        'images' => [
                            'someimage.jpg' => 'image'
                        ]
                    ]
                ]
            ],
            'config.yml' => implode(PHP_EOL, [
                "site-domain: http://flatblog.dev",
                'posts:',
                '   prefix: blog',
                '   templates:',
                '       single: single-post',
                '       all-posts: all-posts',
                '       single-category: single-category',
                '       all-categories: all-categories',
                '   disqus:',
                '       shortname: YOUR_SHORT_NAME',
                '   paginate-lists-at: 5',
                'pages:',
                '   frontpage: parent'
            ]),
        ]);

        $vfsAdapter = new VfsAdapter($this->vfs);
        return new Filesystem($vfsAdapter);
    }

    public function createVirtualFilesystemForPagesPostsAndMedia()
    {
        $this->vfs = new Vfs();

        $this->vfs->createStructure([
            'pages' => [
                'about' => [
                    'settings.yml' => implode(PHP_EOL, [
                        'template: test-page',
                        'header: Test header',
                        'sections: dir:sections',
                        'content: file:content.md'
                    ]),
                    'sections' => [
                        'test1.md' => '## test1',
                        'test2.md' => '## test2',
                    ],
                    'content.md' => '## test',
                    'images' => [
                        'aboutImage.jpg' => 'image'
                    ]
                ],
                'parent' =>[
                    'settings.yml' => implode(PHP_EOL, [
                        'template: test-page',
                        'header: Test header',
                        'test: test',
                    ]),
                    'images' => [
                        'parentImage.png' => 'image',
                    ],
                    'child' => [
                        'settings.yml'=> implode(PHP_EOL, [
                            'template: test-page',
                            'header: Test header',
                            'test: test',
                        ]),
                        'images' => [
                            'childImage.gif' => 'image',
                        ],
                        'skip' => [],
                        'second-child' =>[
                            'settings.yml'=>implode(PHP_EOL, [
                                'template: test-page',
                                'header: Test header',
                                'test: second',
                            ]),
                            'images' => [
                                'second-child-image.bmp' => 'image'
                            ]
                        ]
                    ]
                ]
            ],
            'posts' => [
                'Frontend' => [
                    'sass-tricks-you-should-know' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Sass tricks you should know!',
                            'summary: This is a summary',
                            'image: file:images/someimage.jpg',
                            'content: file:content.md',
                            'date: "2016-03-01"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: keywords',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                        'content.md' => '## Hello World',
                        'images' => [
                            'someimage.jpg' => 'image'
                        ]
                    ]
                ],
                'Backend' => [
                    'do-you-really-need-a-backend-for-that' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Do you really need a backend for that?',
                            'summary: This is a summary',
                            'image: file:images/someimage.jpg',
                            'content: file:content.md',
                            'date: "2016-01-02"'
                        ]),
                        'content.md' => '## Hello World 2',
                        'images' => [
                            'someimage.jpg' => 'image'
                        ]
                    ]
                ]
            ],
            'media' => [
                'document.pdf' => 'pdf file',
                'document.docx' => 'word file',
                'archive.zip' => 'zip file',
                'video.mp4' => 'video file',
            ],
            'config.yml' => implode(PHP_EOL, [
                "site-domain: http://flatblog.dev",
                'posts:',
                '   prefix: blog',
                '   templates:',
                '       single: single-post',
                '       all-posts: all-posts',
                '       single-category: single-category',
                '       all-categories: all-categories',
                '   disqus:',
                '       shortname: YOUR_SHORT_NAME',
                '   paginate-lists-at: 5',
                'pages:',
                '   frontpage: parent'
            ]),
        ]);

        $vfsAdapter = new VfsAdapter($this->vfs);
        return new Filesystem($vfsAdapter);
    }



}