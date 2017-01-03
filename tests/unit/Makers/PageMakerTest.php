<?php
use WackyStudio\Flatblog\Exceptions\CannotFindPagesException;
use WackyStudio\Flatblog\Exceptions\ReferencedParentPageCannotBeFoundException;
use WackyStudio\Flatblog\Makers\PageMaker;

class PageMakerTest extends TestCase
{
    /** @test */
    public function it_creates_a_new_page_with_given_name()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $pageMaker = new PageMaker($fileSystem);
        $expectedSettings = implode(PHP_EOL, [
            "template: 'the name of your template'",
            ''
        ]);

        $pageMaker->makePageWithName('Test Page');

        $this->assertTrue($fileSystem->has('pages/test-page/settings.yml'));
        $this->assertEquals($expectedSettings, $fileSystem->read('pages/test-page/settings.yml'));
    }

    /**
    *@test
    */
    public function it_creates_a_new_sub_page_to_a_parent_page_with_given_name()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $pageMaker = new PageMaker($fileSystem);
        $expectedSettings = implode(PHP_EOL, [
            "template: 'the name of your template'",
            ''
        ]);

        $pageMaker->makePageWithName('Test Page', 'second-child');

        $this->assertTrue($fileSystem->has('pages/parent/child/second-child/test-page/settings.yml'));
        $this->assertEquals($expectedSettings, $fileSystem->read('pages/parent/child/second-child/test-page/settings.yml'));
    }

    /**
    *@test
    */
    public function it_creates_a_new_page_with_a_given_name_and_template()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $pageMaker = new PageMaker($fileSystem);
        $expectedSettings = implode(PHP_EOL, [
            "template: test",
            ''
        ]);

        $pageMaker->makePageWithName('Test Page', null, "test");

        $this->assertTrue($fileSystem->has('pages/test-page/settings.yml'));
        $this->assertEquals($expectedSettings, $fileSystem->read('pages/test-page/settings.yml'));
    }

    /**
    *@test
    */
    public function it_creates_a_new_sub_page_with_given_name_and_template()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $pageMaker = new PageMaker($fileSystem);
        $expectedSettings = implode(PHP_EOL, [
            "template: test",
            ''
        ]);

        $pageMaker->makePageWithName('Test Page', 'second-child', "test");

        $this->assertTrue($fileSystem->has('pages/parent/child/second-child/test-page/settings.yml'));
        $this->assertEquals($expectedSettings, $fileSystem->read('pages/parent/child/second-child/test-page/settings.yml'));
    }

    /**
    *@test
    */
    public function it_throws_exception_if_pages_directory_cannot_be_found()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $pageMaker = new PageMaker($fileSystem);

        try{
            $pageMaker->makePageWithName('Test Page');
        }catch (CannotFindPagesException $e)
        {
            $this->assertEquals('Cannot find pages folder in current location', $e->getMessage());
            return;
        }

        $this->fail('Did not throw exception for missing pages folder');
    }

    /**
    *@test
    */
    public function it_throws_exception_if_parent_page_cannot_be_found()
    {
        $fileSystem = $this->createVirtualFilesystemForPages();
        $pageMaker = new PageMaker($fileSystem);

        try{
            $pageMaker->makePageWithName('Test Page', 'this-parent-should-not-exist');
        }catch (ReferencedParentPageCannotBeFoundException $e)
        {
            $this->assertEquals('Cannot find referenced parent page with name of: this-parent-should-not-exist', $e->getMessage());
            return;
        }

       $this->fail('Did not throw exception when referenced parent page, is not found');
    }
}