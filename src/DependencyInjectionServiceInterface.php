<?php
namespace RonRademaker\DependencyInjectionService;


interface DependencyInjectionServiceInterface
{
    /**
     * Use setter injection to populate services in and $object
     *
     * @param object $container
     * @param object $object
     */
    public function populateServices($container, $object);

    /**
     * Registers an interface with a key and a setter
     * Will overwrite the interface if already known
     *
     * @param string $interface - full namespace for a service awareness interface
     * @param string $key - key in the container builder
     * @param string $setter - setter defined in the $interface to set the service
     * @param boolean $static
     */
    public function registerService($interface, $key, $setter, $static = false);
}