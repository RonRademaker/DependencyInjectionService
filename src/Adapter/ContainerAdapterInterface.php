<?php
namespace RonRademaker\DependencyInjectionService\Adapter;

/**
 * Adapter to attach different kinds of DICs
 *
 * @author Ron Rademaker
 */
interface ContainerAdapterInterface
{
    /**
     * Test if the container has $service
     *
     * @param string $service
     * @return boolean
     */
    public function has($service);

    /**
     * Gets $service from the container
     *
     * @param string $service
     * @return object
     */
    public function get($service);

}
