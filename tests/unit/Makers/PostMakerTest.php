<?php
use Carbon\Carbon;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Exceptions\CannotFindPostsException;
use WackyStudio\Flatblog\Makers\PostMaker;

class PostMakerTest extends TestCase
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
    public function it_takes_a_post_name_as_a_string_and_creates_a_new_post_with_correct_path_settings_file_and_content_file()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $maker = new PostMaker($fileSystem);
        $expectedDate = Carbon::now()->format('Y-m-d');
        $expectedSettingsFile = implode(PHP_EOL, [
            "title: 'Test Post'",
            "summary: 'Create your post summary here...'",
            "image: 'Give a path to your post image here, like images/image.jpg'",
            "content: 'file:content'",
            "date: '{$expectedDate}'",
            ''
        ]);

        $name = 'Test Post';
        $maker->makeNewPostNamed($name);

        $this->assertTrue($fileSystem->has('posts/test-post/settings.yml'));
        $this->assertTrue($fileSystem->has('posts/test-post/content.md'));
        $this->assertEquals($expectedSettingsFile, $fileSystem->read('posts/test-post/settings.yml'));
    }
    
    /**
    *@test
    */
    public function it_takes_a_post_name_and_a_category_and_creates_a_new_post_with_correct_path_settings_file_and_content_file()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $maker = new PostMaker($fileSystem);
        $expectedDate = Carbon::now()->format('Y-m-d');
        $expectedSettingsFile = implode(PHP_EOL, [
            "title: 'Test Post'",
            "summary: 'Create your post summary here...'",
            "image: 'Give a path to your post image here, like images/image.jpg'",
            "content: 'file:content'",
            "date: '{$expectedDate}'",
            ''
        ]);

        $name = 'Test Post';
        $category = 'Test Category';
        $maker->makeNewPostNamed($name, $category);

        $this->assertTrue($fileSystem->has('posts/test-category/test-post/settings.yml'));
        $this->assertTrue($fileSystem->has('posts/test-category/test-post/content.md'));
        $this->assertEquals($expectedSettingsFile, $fileSystem->read('posts/test-category/test-post/settings.yml'));
    }

    /**
    *@test
    */
    public function it_throws_exception_when_posts_directory_is_not_found()
    {
        $fileSystem = $this->createVirtualFilesystemForPages([
            'empty' => []
        ]);

        $maker = new PostMaker($fileSystem);

        $name = 'This should fail';
        try{
            $maker->makeNewPostNamed($name);
        }catch (CannotFindPostsException $e)
        {
            $this->assertEquals('Cannot find posts folder in current location', $e->getMessage());
            return;
        }

        $this->fail('Did not throw exception, when post folder is not found');
    }


}