<?php
use Carbon\Carbon;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Exceptions\PostIsMissingContentException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingImageException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingSummaryException;
use WackyStudio\Flatblog\Exceptions\PostIsMissingTitleException;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\File;
use WackyStudio\Flatblog\Parsers\ParserManager;

class PostEntityFactoryTest extends TestCase
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
    public function it_makes_a_post_entity_from_a_raw_entity()
    {
        $parserManager = Mockery::mock(ParserManager::class)->shouldReceive('parseFiles')->andReturn([
           'content' => 'This is some random content',
        ])->getMock();
        $dateTime = Carbon::now();
        $factory = new PostEntityFactory($parserManager);
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg'
            ],
            [
                new File('posts/subject/test/content.md', time(), 16, 'test', 'content.md', 'md', 'content'),
                new File('posts/subject/test/someimage.jpg', time(), 16, 'test', 'someimage.jpg', 'jpg', 'someimage')
            ],
            $dateTime);

        $postEntity = $factory->make($rawEntity);

        $this->assertEquals('Test', $postEntity->getTitle());
        $this->assertEquals($dateTime, $postEntity->getDate());
        $this->assertEquals('subject', $postEntity->getCategory());
        $this->assertEquals('posts/subject/test', $postEntity->destination());
    }

    /**
    *@test
    */
    public function it_throws_a_missing_post_title_exception_if_title_is_missing()
    {
        $parserManager = Mockery::mock(ParserManager::class)->shouldReceive('parseFiles')->andReturn([
            'content' => 'This is some random content',
        ])->getMock();
        $dateTime = Carbon::now();
        $factory = new PostEntityFactory($parserManager);
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg'
            ],
            [
                new File('posts/subject/test/content.md', time(), 16, 'test', 'content.md', 'md', 'content'),
                new File('posts/subject/test/someimage.jpg', time(), 16, 'test', 'someimage.jpg', 'jpg', 'someimage')
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
        $parserManager = Mockery::mock(ParserManager::class)->shouldReceive('parseFiles')->andReturn([
            'content' => 'This is some random content',
        ])->getMock();
        $dateTime = Carbon::now();
        $factory = new PostEntityFactory($parserManager);
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'image' => 'someimage.jpg'
            ],
            [
                new File('posts/subject/test/content.md', time(), 16, 'test', 'content.md', 'md', 'content'),
                new File('posts/subject/test/someimage.jpg', time(), 16, 'test', 'someimage.jpg', 'jpg', 'someimage')
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
        $parserManager = Mockery::mock(ParserManager::class)->shouldReceive('parseFiles')->andReturn([

        ])->getMock();
        $dateTime = Carbon::now();
        $factory = new PostEntityFactory($parserManager);
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg'
            ],
            [
                new File('posts/subject/test/someimage.jpg', time(), 16, 'test', 'someimage.jpg', 'jpg', 'someimage')
            ],
            $dateTime);
        try{
            $postEntity = $factory->make($rawEntity);
        }catch(PostIsMissingContentException $e){
            $this->assertEquals('Post is missing content.md file or it cannot be parsed', $e->getMessage());
            return;
        }

        $this->fail('An exception for missing content was not thrown');
    }

    /**
    *@test
    */
    public function it_throws_an_exception_if_image_is_missing()
    {
        $parserManager = Mockery::mock(ParserManager::class)->shouldReceive('parseFiles')->andReturn([
            'content' => 'This is some random content',
        ])->getMock();
        $dateTime = Carbon::now();
        $factory = new PostEntityFactory($parserManager);
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
            ],
            [
                new File('posts/subject/test/content.md', time(), 16, 'test', 'content.md', 'md', 'content'),
                new File('posts/subject/test/someimage.jpg', time(), 16, 'test', 'someimage.jpg', 'jpg', 'someimage')
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

}