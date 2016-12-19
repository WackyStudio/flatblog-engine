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
                            'content: test',
                            'date: "2016-03-01"'
                        ]),
                    ],
                    'post-2' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test2',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-02"'
                        ]),
                    ],
                    'post-3' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test3',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-03"'
                        ]),
                    ],
                    'post-4' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test4',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-04"'
                        ]),
                    ],
                    'post-5' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test5',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-05"'
                        ]),
                    ],
                    'post-6' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test6',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-06"'
                        ]),
                    ],
                    'post-7' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test7',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-07"'
                        ]),
                    ],
                    'post-8' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test8',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-08"'
                        ]),
                    ],
                    'post-9' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test9',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-09"'
                        ]),
                    ],
                    'post-10' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test10',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-10"'
                        ]),
                    ],
                    'post-11' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test11',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-11"'
                        ]),
                    ],
                    'post-12' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test12',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-12"'
                        ]),
                    ],
                    'post-13' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test13',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-13"'
                        ]),
                    ],
                    'post-14' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test14',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-14"'
                        ]),
                    ],
                    'post-15' => [
                        'settings.yml' => implode(PHP_EOL, [
                            'title: Test15',
                            'summary: Test',
                            'image: /test.jpg',
                            'content: test',
                            'date: "2016-03-15"'
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
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $expectedContentForBackendPost = implode(PHP_EOL, [
            '<h1>Do you really need a backend for that?</h1>',
            '<h2>Hello World 2</h2>'
        ]);

        $expectedContentForFrontendPost = implode(PHP_EOL, [
            '<h1>Sass tricks you should know!</h1>',
            '<h2>Hello World</h2>'
        ]);

        $singlePosts = $postBuilder->buildSinglePosts();

        $this->assertArrayHasKey('blog/backend/do-you-really-need-a-backend-for-that', $singlePosts);
        $this->assertArrayHasKey('blog/frontend/sass-tricks-you-should-know', $singlePosts);

        $this->assertEquals($expectedContentForBackendPost, $singlePosts['blog/backend/do-you-really-need-a-backend-for-that']);
        $this->assertEquals($expectedContentForFrontendPost, $singlePosts['blog/frontend/sass-tricks-you-should-know']);

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
            return new TemplateRenderer(new BladeInstance($path, $cache));
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
            return new TemplateRenderer(new BladeInstance($path, $cache));
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
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $expectedContentBackend = implode(PHP_EOL, [
           '<h1>Backend</h1>',
           '<div>',
           '<h1>Do you really need a backend for that?</h1>',
           '<p>This is a summary</p>',
           '</div>',
           ''
        ]);

        $expectedContentFrontend = implode(PHP_EOL, [
            '<h1>Frontend</h1>',
            '<div>',
            '<h1>Sass tricks you should know!</h1>',
            '<p>This is a summary</p>',
            '</div>',
            ''
        ]);

        $categories = $postBuilder->buildSingleCategories();

        $this->assertEquals($expectedContentBackend, $categories['blog/backend']);
        $this->assertEquals($expectedContentFrontend, $categories['blog/frontend']);
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
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $expectedContent = implode(PHP_EOL, [
            '<ul>',
            '<li>Backend (1)</li>',
            '<li>Frontend (1)</li>',
            '</ul>'
        ]);

        $result = $postBuilder->buildCategoryList();

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
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], $this->app->getContainer()['config']);

        $toWrite = $postBuilder->build();

        $this->assertArrayHasKey('blog/backend/do-you-really-need-a-backend-for-that', $toWrite);
        $this->assertArrayHasKey('blog/frontend/sass-tricks-you-should-know', $toWrite);
        $this->assertArrayHasKey('blog/frontend', $toWrite);
        $this->assertArrayHasKey('blog/backend', $toWrite);
        $this->assertArrayHasKey('blog/categories', $toWrite);
        $this->assertArrayHasKey('blog', $toWrite);

    }
}