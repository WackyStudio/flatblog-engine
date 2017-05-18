<?php
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\MediaFolderBuilder;

class MediaFolderBuilderTest extends TestCase
{

    /** @test */
    public function it_copies_content_media_folder_in_project_root_into_media_folder_in_build_folder()
    {
        $fileSystem = $this->createVirtualFilesystemForPagesPostsAndMedia();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };

        $builder = new MediaFolderBuilder($fileSystem);
        $builder->build();

        $this->assertTrue($fileSystem->has('build/media/document.pdf'));
        $this->assertTrue($fileSystem->has('build/media/document.docx'));
        $this->assertTrue($fileSystem->has('build/media/archive.zip'));
        $this->assertTrue($fileSystem->has('build/media/video.mp4'));

    }

    /**
     * @test
     */
    public function it_respects_if_no_media_folder_is_present_in_project_root()
    {
        $fileSystem = $this->createVirtualFilesystemForPagesAndPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };

        $builder = new MediaFolderBuilder($fileSystem);
        $builder->build();
    }

}