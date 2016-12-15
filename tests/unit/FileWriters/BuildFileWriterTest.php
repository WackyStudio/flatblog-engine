<?php
use WackyStudio\Flatblog\Exceptions\KeyMissingInFileArrayException;
use WackyStudio\Flatblog\FileWriters\BuildFileWriter;

class BuildFileWriterTest extends TestCase
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
    *@test
    */
    public function it_writes_contents_into_single_file_and_puts_it_into_directory_structure_to_match_given_url()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $writer = new BuildFileWriter($fileSystem);
        $url = 'about';
        $content = '<h1>test</h1>';

        $writer->writeSingleFile($url, $content);

        $this->assertTrue($fileSystem->has('build/about/index.html'));
        $this->assertEquals($fileSystem->read('build/about/index.html'), $content);
    }

    /**
    *@test
    */
    public function it_writes_contents_of_each_entity_in_array_of_multiple_files_and_puts_each_into_directory_structure_matching_its_url()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $writer = new BuildFileWriter($fileSystem);
        $entities = [
            'blog/frontend/sass-rocks' => '<h1>Sass</h1>',
            'blog/frontend/vue-rocks-more' => '<h1>Vue</h1>',
            'blog/backend/publier-rocks-the-most' => '<h1>Publier</h1>',
        ];

        $writer->writeMultipleFiles($entities);

        $this->assertTrue($fileSystem->has('build/blog/frontend/sass-rocks/index.html'));
        $this->assertTrue($fileSystem->has('build/blog/frontend/vue-rocks-more/index.html'));
        $this->assertTrue($fileSystem->has('build/blog/backend/publier-rocks-the-most/index.html'));
        $this->assertEquals($fileSystem->read('build/blog/frontend/sass-rocks/index.html'), '<h1>Sass</h1>');
        $this->assertEquals($fileSystem->read('build/blog/frontend/vue-rocks-more/index.html'), '<h1>Vue</h1>');
        $this->assertEquals($fileSystem->read('build/blog/backend/publier-rocks-the-most/index.html'), '<h1>Publier</h1>');
    }

    /**
    *@test
    */
    public function it_throws_exception_if_no_url_is_given_as_key_in_array_for_multiple_files()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $writer = new BuildFileWriter($fileSystem);
        $entities = [
            '<h1>Sass</h1>',
            '<h1>Vue</h1>',
            '<h1>Publier</h1>',
        ];


        try{
            $writer->writeMultipleFiles($entities);
        }catch (KeyMissingInFileArrayException $e){
            $this->assertEquals('No key in form of URL path, was given in array', $e->getMessage());
            return;
        }

        $this->fail('No exception for missing key, was thrown');
    }

}