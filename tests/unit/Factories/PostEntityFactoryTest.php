<?php

use Cake\Chronos\Chronos;
use Mockery\Mock;
use WackyStudio\Flatblog\Core\Config;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Exceptions\InvalidDateGivenInSettingsFileException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingContentException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingImageException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingSummaryException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingTitleException;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Parsers\ParserManager;

class PostEntityFactoryTest extends TestCase
{

    /**
     * @var Mock
     */
    private $config;

    public function setUp()
    {
        parent::setUp();

        $this->config = Mockery::mock(Config::class);
    }

    protected function mockConfigWithPostPrefix()
    {
        $this->config->shouldReceive('get')
                     ->with('posts.prefix')
                     ->andReturn('blog');
    }

    protected function mockConfigWithoutPostPrefix()
    {
        $this->config->shouldReceive('get')
                     ->with('posts.prefix')
                     ->andReturnNull();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_makes_a_post_entity_from_a_raw_entity_with_posts_prefix()
    {
        $this->mockConfigWithPostPrefix();

        $dateTime = Chronos::now();
        $factory = new PostEntityFactory($this->config);
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg',
                'thumbnail' => 'someimage-thumb.jpg',
                'content' => 'file:content.md',

                'alt' => 'this is the alt text',
                'featured_post' => 'true',
                'seo_title' => 'SEO Title',
                'seo_description' => 'SEO Description',
                'seo_keywords' => 'keywords',
                'fb_url' => 'facebook url',
                'header_image' => '/images/something.jpg',
            ],
            $dateTime);

        $postEntity = $factory->make($rawEntity);

        $this->assertEquals('Test', $postEntity->title);
        $this->assertEquals($dateTime, $postEntity->date);
        $this->assertEquals('Subject', $postEntity->category);
        $this->assertEquals('blog/subject/test', $postEntity->destination());
        $this->assertEquals('blog/subject', $postEntity->categoryLink);

    }

    /**
    *@test
    */
    public function it_makes_a_post_entity_from_a_raw_entity_without_posts_prefix()
    {
        $this->mockConfigWithoutPostPrefix();

        $dateTime = Chronos::now();
        $factory = new PostEntityFactory($this->config);
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg',
                'thumbnail' => 'someimage-thumb.jpg',
                'content' => 'file:content.md',

                'alt' => 'this is the alt text',
                'featured_post' => 'true',
                'seo_title' => 'SEO Title',
                'seo_description' => 'SEO Description',
                'seo_keywords' => 'keywords',
                'fb_url' => 'facebook url',
                'header_image' => '/images/something.jpg',
            ],
            $dateTime);

        $postEntity = $factory->make($rawEntity);

        $this->assertEquals('Test', $postEntity->title);
        $this->assertEquals($dateTime, $postEntity->date);
        $this->assertEquals('Subject', $postEntity->category);
        $this->assertEquals('subject/test', $postEntity->destination());
        $this->assertEquals('subject', $postEntity->categoryLink);
    }

    /**
    *@test
    */
    public function it_throws_a_missing_post_title_exception_if_title_is_missing()
    {
        $this->mockConfigWithPostPrefix();

        $dateTime = Chronos::now();
        $factory = new PostEntityFactory($this->config);;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg'
            ],
            $dateTime);
        try{
            $postEntity = $factory->make($rawEntity);
        }catch (PostIsMissingTitleException $e)
        {
            $this->assertEquals('Post is missing title', $e->getMessage());
            return;
        }

        $this->fail('An exception for missing title was not thrown!');
    }

    /**
    *@test
    */
    public function it_throws_an_exception_if_summary_is_missing()
    {
        $this->mockConfigWithPostPrefix();

        $dateTime = Chronos::now();
        $factory = new PostEntityFactory($this->config);;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'image' => 'someimage.jpg'
            ],
            $dateTime);
        try{
            $postEntity = $factory->make($rawEntity);
        }catch (PostIsMissingSummaryException $e)
        {
            $this->assertEquals('Post is missing summary', $e->getMessage());
            return;
        }

        $this->fail('An exception for missing summary was not thrown');
    }

    /**
    *@test
    */
    public function it_throws_an_exception_if_content_is_missing()
    {
        $this->mockConfigWithPostPrefix();

        $dateTime = Chronos::now();
        $factory = new PostEntityFactory($this->config);;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg',
                'thumbnail' => 'someimage-thumb.jpg',
            ],
            $dateTime);
        try{
            $postEntity = $factory->make($rawEntity);
        }catch(PostIsMissingContentException $e){
            $this->assertEquals('Post is missing content', $e->getMessage());
            return;
        }

        $this->fail('An exception for missing content was not thrown');
    }

    /**
    *@test
    */
    public function it_throws_an_exception_if_image_is_missing()
    {
        $this->mockConfigWithPostPrefix();

        $dateTime = Chronos::now();
        $factory = new PostEntityFactory($this->config);;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
            ],
            $dateTime);
        try{
            $postEntity = $factory->make($rawEntity);
        }catch (PostIsMissingImageException $e) {
            $this->assertEquals('Post is missing image', $e->getMessage());
            return;
        }

        $this->fail('An exception for missing image was not thrown');
    }

    /**
    *@test
    */
    public function it_takes_date_from_post_settings_if_it_is_available()
    {
        $this->mockConfigWithPostPrefix();

        $dateTime = Chronos::parse('2015-01-05');
        $factory = new PostEntityFactory($this->config);;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg',
                'thumbnail' => 'someimage-thumb.jpg',
                'content' => 'file:content.md',
                'date' => '2015-01-06',

                'alt' => 'this is the alt text',
                'featured_post' => 'true',
                'seo_title' => 'SEO Title',
                'seo_description' => 'SEO Description',
                'seo_keywords' => 'keywords',
                'fb_url' => 'facebook url',
                'header_image' => '/images/something.jpg',
            ],
            $dateTime);

        $postEntity = $factory->make($rawEntity);

        $this->assertEquals('Test', $postEntity->title);
        $this->assertNotEquals($dateTime, $postEntity->date);
        $this->assertEquals(Chronos::parse('2015-01-06'), $postEntity->date);
        $this->assertEquals('Subject', $postEntity->category);
        $this->assertEquals('blog/subject/test', $postEntity->destination());
        $this->assertEquals('blog/subject', $postEntity->categoryLink);
    }

    /**
    *@test
    */
    public function it_throws_exception_if_date_given_in_settings_is_not_correct()
    {
        $this->mockConfigWithPostPrefix();
        $dateTime = Chronos::parse('2015-01-05');
        $factory = new PostEntityFactory($this->config);;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg',
                'thumbnail' => 'someimage-thumb.jpg',
                'content' => 'file:content.md',
                'date' => 'failfailfail',

                'alt' => 'this is the alt text',
                'featured_post' => 'true',
                'seo_title' => 'SEO Title',
                'seo_description' => 'SEO Description',
                'seo_keywords' => 'keywords',
                'fb_url' => 'facebook url',
                'header_image' => '/images/something.jpg',
            ],
            $dateTime);
        try{
            $postEntity = $factory->make($rawEntity);
        }catch (InvalidDateGivenInSettingsFileException $e)
        {
            $this->assertEquals('Invalid date given in settings file for posts/subject/test', $e->getMessage());
            return;
        }

        $this->fail('No exception was thrown for invalid date');
    }



}