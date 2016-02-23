<?php
namespace RonRademaker\DependencyInjectionService;

use RonRademaker\DependencyInjectionService\Adapter\ContainerAdapterInterface;

/**
 * Representation of the definition of a setter injection
 *
 * @author Ron Rademaker
 */
class DependencyInjectionServiceDefinition
{
    /**
     * The service to inject
     *
     * @var string
     */
    private $service;

    /**
     * The setter to inject with
     *
     * @var string
     */
    private $setter;

    /**
     * Boolean indicating a static injection
     *
     * @var boolean
     */
    private $static;

    /**
     * Create a new DependencyInjectionServiceDefinition
     */
    public function __construct($service, $setter, $static)
    {
        $this->service = $service;
        $this->setter = $setter;
        $this->static = $static;
    }

    /**
     * Populate $object from $container according to this definition
     *
     * @param ContainerAdapterInterface $adapter
     * @param object $object
     */
    public function populate(ContainerAdapterInterface $adapter, $object)
    {
        if ($adapter->has($this->service)) {
            $service = $adapter->get($this->service);

            if ($this->static === true) {
                $object::{$this->setter}($service);
            } else {
                $object->{$this->setter}($service);
            }
        }
    }
}
