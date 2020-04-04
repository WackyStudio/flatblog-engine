<?php
use duncan3dc\Laravel\BladeInstance;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\PostsBuilder;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

class PostBuilderTest extends TestCase
{
    private function buildMultiplePostsFileSystem()
    {
        return [
            'posts' => [
                'category' => [
                    'post-1' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test1',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-01"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-2' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test2',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'alt: this is the alt text',
                            'content: test',
                            'date: "2016-03-02"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-3' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test3',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-03"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-4' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test4',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-04"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-5' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test5',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-05"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-6' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test6',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-06"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-7' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test7',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-07"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-8' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test8',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-08"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-9' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test9',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-09"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-10' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test10',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-10"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-11' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test11',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-11"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-12' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test12',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-12"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-13' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test13',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-13"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-14' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test14',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-14"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],
                    'post-15' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test15',
                            'summary: Test',
                            'image: /test.jpg',
                            'thumbnail: /test.jpg',
                            'content: test',
                            'date: "2016-03-15"',

                            'alt: this is the alt text',
                            'featured_post: true',
                            'seo_title: SEO Title',
                            'seo_description: SEO Description',
                            'seo_keywords: Flatblog, is, awesome',
                            'fb_url: facebook url',
                            'header_image: /images/something.jpg',
                        ]),
                    ],

                ]
            ],
        ];
    }

    /**
    *@test
    */
    public function it_builds_single_posts_with_correct_url_key()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
          return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache), $this->app->getContainer()['config']);
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $expectedContentForBackendPost = implode(PHP_EOL, [
            '<p>SEO Title</p>',
            '<p>SEO Description</p>',
            '<p>keywords</p>',
            '<p>facebook url</p>',
            '<p>/images/something.jpg</p>',
            '<p>someimage-thumb</p>',
            '<p>this is the alt text</p>',
            '<h1>Do you really need a backend for that?</h1>',
            '<h2>Hello World 2</h2>'
        ]);

        $expectedContentForFrontendPost = implode(PHP_EOL, [
            '<p>SEO Title</p>',
            '<p>SEO Description</p>',
            '<p>keywords</p>',
            '<p>facebook url</p>',
            '<p>/images/something.jpg</p>',
            '<p>someimage-thumb</p>',
            '<p>this is the alt text</p>',
            '<h1>Sass tricks you should know!</h1>',
            '<h2>Hello World</h2>'
        ]);

        $singlePosts = $postBuilder->buildSinglePosts();

        $this->assertArrayHasKey('blog/back-end/do-you-really-need-a-backend-for-that', $singlePosts);
        $this->assertArrayHasKey('blog/front-end/sass-tricks-you-should-know', $singlePosts);

        $this->assertEquals($expectedContentForBackendPost, $singlePosts['blog/back-end/do-you-really-need-a-backend-for-that']);
        $this->assertEquals($expectedContentForFrontendPost, $singlePosts['blog/front-end/sass-tricks-you-should-know']);

    }


    /** @test */
    public function it_builds_single_posts_with_correct_url_key_and_respects_danish_letters()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts([
            'posts' => [
                'Æøå' => [
                    'sass-tricks-you-should-know' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Sass tricks you should know!',
                            'summary: This is a summary',
                            'image: file:someimage.jpg',
                            'thumbnail: file:someimage-thumb.jpg',
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
                        'someimage.jpg' => 'image',
                        'someimage-thumb.jpg' => 'image'
                    ]
                ],
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
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache), $this->app->getContainer()['config']);
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $expectedContentForFrontendPost = implode(PHP_EOL, [
            '<p>SEO Title</p>',
            '<p>SEO Description</p>',
            '<p>keywords</p>',
            '<p>facebook url</p>',
            '<p>/images/something.jpg</p>',
            '<p>someimage-thumb</p>',
            '<p>this is the alt text</p>',
            '<h1>Sass tricks you should know!</h1>',
            '<h2>Hello World</h2>'
        ]);

        $singlePosts = $postBuilder->buildSinglePosts();

        $this->assertArrayHasKey('blog/aeoeaa/sass-tricks-you-should-know', $singlePosts);

        $this->assertEquals($expectedContentForFrontendPost, $singlePosts['blog/aeoeaa/sass-tricks-you-should-know']);

    }

    /**
    *@test
    */
    public function it_builds_page_with_list_of_all_post_and_sorts_them_descending_by_date()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache), $this->app->getContainer()['config']);
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $expectedContent = implode(PHP_EOL, [
            '<div>',
            '        <h1>Sass tricks you should know!</h1>',
            '        <p>This is a summary</p>',
            '    </div>',
            '    <div>',
            '        <h1>Do you really need a backend for that?</h1>',
            '        <p>This is a summary</p>',
            '    </div>',
            ''
        ]);

        $result = $postBuilder->buildPostsList();

        $this->assertEquals($expectedContent, $result['blog']);
    }

    /**
    * @test
    */
    public function it_paginates_list_of_all_posts_when_limit_defined_in_config_file_is_reached()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts($this->buildMultiplePostsFileSystem());

        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache), $this->app->getContainer()['config']);
        };
        $config = Mockery::mock(Config::class);
        $config->shouldReceive('get')->with('posts.paginate-lists-at')->andReturn(10);
        $config->shouldReceive('get')->with('posts.templates')->andReturn([
            'single'=> 'single-post',
            'all-posts'=> 'all-posts',
            'single-category'=> 'single-category',
            'all-categories' => 'all-categories',
        ]);
        $config->shouldReceive('get')->with('posts.prefix')->andReturn('blog');

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $config);

        $expectedContentPageOne = implode(PHP_EOL, [
            '<div>',
            '        <h1>Test15</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test14</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test13</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test12</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test11</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test10</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test9</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test8</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test7</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test6</h1>',
            '        <p>Test</p>',
            '    </div>',
            ''
        ]);

        $expectedContentPageTwo = implode(PHP_EOL, [
            '<div>',
            '        <h1>Test5</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test4</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test3</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test2</h1>',
            '        <p>Test</p>',
            '    </div>',
            '    <div>',
            '        <h1>Test1</h1>',
            '        <p>Test</p>',
            '    </div>',
            ''
        ]);

        $result = $postBuilder->buildPostsList();

        $this->assertEquals($expectedContentPageOne, $result['blog']);
        $this->assertEquals($expectedContentPageTwo, $result['blog/page/2']);
    }

    /**
    * @test
    */
    public function it_includes_array_of_categories_on_all_posts_page()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache), $this->app->getContainer()['config']);
        };

        $config = Mockery::mock(Config::class);
        $config->shouldReceive('get')->with('posts.paginate-lists-at')->andReturn(10);
        $config->shouldReceive('get')->with('posts.templates')->andReturn([
            'single'=> 'single-post',
            'all-posts'=> 'all-posts-with-categories',
            'single-category'=> 'single-category',
            'all-categories' => 'all-categories',
        ]);
        $config->shouldReceive('get')->with('posts.prefix')->andReturn('blog');

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $config);

        $expectedContent = implode(PHP_EOL, [
            '<ul>',
            '<li><a href="back-end">Back end (1)</a></li>',
            '<li><a href="front-end">Front end (1)</a></li>',
            '</ul>',
            '<div>',
            '<h1>Sass tricks you should know!</h1>',
            '<p>This is a summary</p>',
            '</div>',
            '<div>',
            '<h1>Do you really need a backend for that?</h1>',
            '<p>This is a summary</p>',
            '</div>',
            ''
        ]);

        $result = $postBuilder->buildPostsList();

        $this->assertEquals($expectedContent, $result['blog']);
    }

    /**
    *@test
    */
    public function it_builds_pages_for_each_category_with_posts_and_sorts_them_alphabetically()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache), $this->app->getContainer()['config']);
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $expectedContentBackend = implode(PHP_EOL, [
           '<h1>Back end</h1>',
           '<div>',
           '<h1>Do you really need a backend for that?</h1>',
           '<p>This is a summary</p>',
           '</div>',
           ''
        ]);

        $expectedContentFrontend = implode(PHP_EOL, [
            '<h1>Front end</h1>',
            '<div>',
            '<h1>Sass tricks you should know!</h1>',
            '<p>This is a summary</p>',
            '</div>',
            ''
        ]);

        $categories = $postBuilder->buildSingleCategories();


        $this->assertEquals($expectedContentBackend, $categories['blog/back-end']);
        $this->assertEquals($expectedContentFrontend, $categories['blog/front-end']);
    }
    
    /**
    *@test
    */
    public function it_builds_list_of_all_categories()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache), $this->app->getContainer()['config']);
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $expectedContent = implode(PHP_EOL, [
            '<ul>',
            '<li>Back end (1)</li>',
            '<li>Front end (1)</li>',
            '</ul>'
        ]);

        $result = $postBuilder->buildListOfAllCategories();

        $this->assertEquals($expectedContent, $result['blog/categories']);
    }

    /**
     * @test
     */
    public function it_runs_all_builds_and_returns_array_of_files_to_written()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache), $this->app->getContainer()['config']);
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $toWrite = $postBuilder->build();

        $this->assertArrayHasKey('blog/back-end/do-you-really-need-a-backend-for-that', $toWrite);
        $this->assertArrayHasKey('blog/front-end/sass-tricks-you-should-know', $toWrite);
        $this->assertArrayHasKey('blog/front-end', $toWrite);
        $this->assertArrayHasKey('blog/back-end', $toWrite);
        $this->assertArrayHasKey('blog/categories', $toWrite);
        $this->assertArrayHasKey('blog', $toWrite);

    }
    
    /**
    *@test
    */
    public function it_includes_an_array_of_categories_on_a_single_category()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache), $this->app->getContainer()['config']);
        };

        $config = Mockery::mock(Config::class);
        $config->shouldReceive('get')->with('posts.paginate-lists-at')->andReturn(10);
        $config->shouldReceive('get')->with('posts.templates')->andReturn([
            'single'=> 'single-post',
            'all-posts'=> 'all-posts-with-categories',
            'single-category'=> 'single-category-with-category-list',
            'all-categories' => 'all-categories',
        ]);
        $config->shouldReceive('get')->with('posts.prefix')->andReturn('blog');

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $config);

        $expectedContentBackend = implode(PHP_EOL, [
            '<ul>',
            '<li><a href="back-end">Back end (1)</a></li>',
            '<li><a href="front-end">Front end (1)</a></li>',
            '</ul>',
            '<h1>Back end</h1>',
            '<div>',
            '<h1>Do you really need a backend for that?</h1>',
            '<p>This is a summary</p>',
            '</div>',
            ''
        ]);

        $expectedContentFrontend = implode(PHP_EOL, [
            '<ul>',
            '<li><a href="back-end">Back end (1)</a></li>',
            '<li><a href="front-end">Front end (1)</a></li>',
            '</ul>',
            '<h1>Front end</h1>',
            '<div>',
            '<h1>Sass tricks you should know!</h1>',
            '<p>This is a summary</p>',
            '</div>',
            ''
        ]);

        $categories = $postBuilder->buildSingleCategories();

        $this->assertEquals($expectedContentBackend, $categories['blog/back-end']);
        $this->assertEquals($expectedContentFrontend, $categories['blog/front-end']);
    }
}