<?php
namespace RonRademaker\DependencyInjectionService;

use RonRademaker\DependencyInjectionService\Adapter\ContainerAdapterFactory;

/**
 * Service to populate objects with services using Dependency Injection
 *
 * @author Ron Rademaer
 */
class DependencyInjectionService implements DependencyInjectionServiceInterface
{
    /**
     * Configuration of the awareness interfaces, services and setters
     *
     * @var array
     */
    private $injectionMapping = [];

    /**
     * Use setter injection to populate services in and $object
     *
     * @param object $container
     * @param object $object
     */
    public function populateServices($container, $object)
    {
        $dicAdapter = ContainerAdapterFactory::createAdapter($container);
        $interfaces = class_implements($object);
        foreach ($this->injectionMapping as $interface => $definition) {
            if (in_array($interface, $interfaces)) {
                $definition->populate($dicAdapter, $object);
            }
        }
    }

    /**
     * Registers an interface with a key and a setter
     * Will overwrite the interface if already known
     *
     * @param string $interface - full namespace for a service awareness interface
     * @param string $key - key in the container builder
     * @param string $setter - setter defined in the $interface to set the service
     * @param boolean $static
     */
    public function registerService($interface, $key, $setter, $static = false)
    {
        $this->injectionMapping[$interface] = new DependencyInjectionServiceDefinition($key, $setter, $static);
    }
}
