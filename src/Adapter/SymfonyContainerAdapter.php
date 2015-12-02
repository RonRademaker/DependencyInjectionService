<?php
namespace RonRademaker\DependencyInjectionService\Adapter;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Adapter to access the Symfony ContainerBuilder
 *
 * @author Ron Rademaker
 */
class SymfonyContainerAdapter implements ContainerAdapterInterface
{
    /**
     * Instance of the container builder
     *
     * @var ContainerBuilder
     */
    private $container;

    /**
     * Create a new SymfonyContainerAdapter
     *
     * @param ContainerBuilder
     */
    public function __construct(ContainerBuilder $container)
    {
       $this->container = $container;
    }

    /**
     * Test if the container has $service
     *
     * @param string $service
     * @return boolean
     */
    public function has($service)
    {
        return $this->container->has($service);
    }

    /**
     * Gets $service from the container
     *
     * @param string $service
     * @return object
     */
    public function get($service)
    {
        return $this->container->get($service);
    }
}
