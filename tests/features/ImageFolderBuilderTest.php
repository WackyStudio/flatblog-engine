<?php
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\ImageFolderBuilder;
use WackyStudio\Flatblog\Factories\RawEntityFactory;

class ImageFolderBuilderTest extends TestCase
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
    public function it_takes_all_images_found_in_files_for_raw_entities_and_moves_them_to_images_folder()
    {
        $fileSystem = $this->createVirtualFilesystemForPagesAndPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawPageEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('pages');
        $rawPostEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $rawEntities = collect([])->merge($rawPageEntities)->merge($rawPostEntities)->toArray();


        $imageFolderBuilder = new ImageFolderBuilder($rawEntities, $fileSystem);

        $imageFolderBuilder->build();

        $this->assertTrue($fileSystem->has('build/images/someimage.jpg'));
        $this->assertTrue($fileSystem->has('build/images/second-child-image.bmp'));
        $this->assertTrue($fileSystem->has('build/images/childImage.gif'));
        $this->assertTrue($fileSystem->has('build/images/parentImage.png'));
        $this->assertTrue($fileSystem->has('build/images/aboutImage.jpg'));
    }

}