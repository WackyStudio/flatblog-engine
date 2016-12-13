<?php
use Carbon\Carbon;
use WackyStudio\Flatblog\Entities\RawEntity;
use WackyStudio\Flatblog\Exceptions\PageIsMissingTemplateException;
use WackyStudio\Flatblog\Factories\PageEntityFactory;

class PageEntityFactoryTest extends PHPUnit_Framework_TestCase
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
    public function it_makes_a_page_entity_from_a_raw_entity()
    {
        $dateTime = Carbon::now();
        $factory = new PageEntityFactory;
        $rawEntity = new RawEntity('pages/about',
            [
                'template' => 'test',
                'header' => 'Test Header',
                'content' => 'file:content.md',
                'someMagicProp' => 'it is magic'
            ],
            $dateTime);

        $pageEntity = $factory->make($rawEntity);

        $this->assertEquals('test', $pageEntity->template);
        $this->assertEquals('Test Header', $pageEntity->header);
        $this->assertEquals('file:content.md', $pageEntity->content);
        $this->assertEquals('it is magic', $pageEntity->someMagicProp);
    }
    
    /**
    *@test
    */
    public function it_throws_missing_template_if_template_is_missing_from_settings()
    {
        $dateTime = Carbon::now();
        $factory = new PageEntityFactory;
        $rawEntity = new RawEntity('pages/about',
            [
                'header' => 'Test Header',
                'content' => 'file:content.md',
                'someMagicProp' => 'it is magic'
            ],
            $dateTime);

        try{
            $pageEntity = $factory->make($rawEntity);
        }catch (PageIsMissingTemplateException $e)
        {
            $this->assertEquals('Page is missing template', $e->getMessage());
            return;
        }

        $this->fail('No exception for missing template, was thrown');
    }

}