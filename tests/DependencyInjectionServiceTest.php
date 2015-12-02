<?php
namespace RonRademaker\DependencyInjectionService\Tests;

use Mockery;
use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;
use RonRademaker\DependencyInjectionService\DependencyInjectionService;
use RonRademaker\DependencyInjectionService\Tests\Fixtures\LoggerAwareInterface;
use RonRademaker\DependencyInjectionService\Tests\Fixtures\MyObject;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Unit test for the injection service
 *
 * @author Ron Rademaker
 */
class DependencyInjectionServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test populate object
     */
    public function testPopulateObject()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $object = new MyObject();
        $container = new ContainerBuilder();
        $container->set('logger', $logger);

        $injectionService = new DependencyInjectionService();
        $injectionService->registerService(LoggerAwareInterface::class, 'logger', 'setLogger');

        $injectionService->populateServices($container, $object);

        $this->assertEquals($logger, $object->getLogger());
    }

    /**
     * Test populating object with an unset service
     */
    public function testPopulatingObjectIsOptional()
    {
        $object = new MyObject();
        $container = new ContainerBuilder();

        $injectionService = new DependencyInjectionService();
        $injectionService->registerService(LoggerAwareInterface::class, 'logger', 'setLogger');

        $injectionService->populateServices($container, $object);

        $this->assertNull($object->getLogger());
    }

     /**
     * Test populate object using a static setter
     */
    public function testPopulateObjectStatic()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $container = new ContainerBuilder();
        $container->set('logger', $logger);

        $injectionService = new DependencyInjectionService();
        $injectionService->registerService(LoggerAwareInterface::class, 'logger', 'staticLogger', true);

        $injectionService->populateServices($container, MyObject::class);

        $this->assertEquals($logger, MyObject::getStaticLogger());
    }
}
