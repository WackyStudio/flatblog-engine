<?php
use Silly\Edition\Pimple\Application;
use WackyStudio\Flatblog\Commands\FakeCommand;
use WackyStudio\Flatblog\Core\ApplicationFactory;
use WackyStudio\Flatblog\Core\Config;

class ApplicationFactoryTest extends PHPUnit_Framework_TestCase
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
    public function create_an_instance_of_application()
    {
        $factory = new ApplicationFactory();

        $application = $factory->boot();

        $this->assertInstanceOf(Application::class, $application);
    }

    /**
    *@test
    */
    public function set_current_working_directory()
    {
        $factory = new ApplicationFactory();

        $application = $factory->boot();
        $container = $application->getContainer();

        $this->assertEquals($container['CWD'], getcwd());

    }

    /**
    *@test
    */
    public function set_dependencies()
    {
        $dependencies = [
            WackyStudio\Flatblog\Commands\FakeCommand::class => function($container){
                return new FakeCommand();
            }
        ];

        $factory = new ApplicationFactory($dependencies);
        $application = $factory->boot();
        $container = $application->getContainer();

        $this->assertInstanceOf(FakeCommand::class, $container[FakeCommand::class]);
    }
}