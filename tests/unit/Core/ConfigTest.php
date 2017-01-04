<?php
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Exceptions\NoValueMatchedGivenConfigKeysException;

class ConfigTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_parses_config_file()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $config = new Config($fileSystem);

        $this->assertEquals([
            'site-domain' => 'http://flatblog.dev',
            'posts' => [
                'prefix' => 'blog',
                'templates' => [
                    'single'=> 'single-post',
                    'all-posts' => 'all-posts',
                    'single-category' => 'single-category',
                    'all-categories' => 'all-categories',
                ],
                'paginate-lists-at' => 5
            ]
        ], $config->all());
    }

    /**
    *@test
    */
    public function it_gets_config_values_by_dot_notations()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts([
            'config.yml' => implode(PHP_EOL,[
                'parent:',
                '   child:',
                '       subChild:',
                '           nested: test'
            ])
        ]);
        $config = new Config($fileSystem);

        $this->assertEquals('test', $config->get('parent.child.subChild.nested'));
    }

    /**
    *@test
    */
    public function it_returns_null_if_no_value_are_found_for_keys()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts([
            'config.yml' => implode(PHP_EOL,[
                'parent:',
                '   child:',
                '       subChild:',
                '           nested: test'
            ])
        ]);
        $config = new Config($fileSystem);

        $this->assertNull($config->get('this.does.not.exits'));
    }

    /**
     * @test
     */
    public function it_creates_singleton_with_parsed_config_file()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $config = Config::getInstance($fileSystem);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals('blog', $config->get('posts.prefix'));
    }

}