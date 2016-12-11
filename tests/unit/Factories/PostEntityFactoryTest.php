<?php
use Carbon\Carbon;
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
        $dateTime = Carbon::now();
        $factory = new PostEntityFactory;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg',
                'content' => 'file:content.md'
            ],
            $dateTime);

        $postEntity = $factory->make($rawEntity);

        $this->assertEquals('Test', $postEntity->title);
        $this->assertEquals($dateTime, $postEntity->date);
        $this->assertEquals('subject', $postEntity->category);
        $this->assertEquals('posts/subject/test', $postEntity->destination());
    }

    /**
    *@test
    */
    public function it_throws_a_missing_post_title_exception_if_title_is_missing()
    {
        $dateTime = Carbon::now();
        $factory = new PostEntityFactory;
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

        $dateTime = Carbon::now();
        $factory = new PostEntityFactory;
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
        $dateTime = Carbon::now();
        $factory = new PostEntityFactory;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg'
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
        $dateTime = Carbon::now();
        $factory = new PostEntityFactory;
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
        $dateTime = Carbon::parse('2015-01-05');
        $factory = new PostEntityFactory;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg',
                'content' => 'file:content.md',
                'date' => '2015-01-06'
            ],
            $dateTime);

        $postEntity = $factory->make($rawEntity);

        $this->assertEquals('Test', $postEntity->title);
        $this->assertNotEquals($dateTime, $postEntity->date);
        $this->assertEquals(Carbon::parse('2015-01-06'), $postEntity->date);
        $this->assertEquals('subject', $postEntity->category);
        $this->assertEquals('posts/subject/test', $postEntity->destination());
    }

    /**
    *@test
    */
    public function it_throws_exception_if_date_given_in_settings_is_not_correct()
    {
        $dateTime = Carbon::parse('2015-01-05');
        $factory = new PostEntityFactory;
        $rawEntity = new RawEntity('posts/subject/test',
            [
                'title' => 'Test',
                'summary' => 'This is a summary',
                'image' => 'someimage.jpg',
                'content' => 'file:content.md',
                'date' => 'failfailfail'
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