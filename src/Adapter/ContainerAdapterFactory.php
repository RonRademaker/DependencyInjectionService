<?php
namespace RonRademaker\DependencyInjectionService\Adapter;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Factory to create the correct adapter for a container
 *
 * @author Ron Rademaker
 */
class ContainerAdapterFactory
{
    /**
     * Gets the correct adapter
     *
     * @param object $container
     * @return ContainerAdapterInterface
     */
    public static function createAdapter($container)
    {
        if ($container instanceof ContainerBuilder) {
            return new SymfonyContainerAdapter($container);
            // @codeCoverageIgnoreStart
        }
    }
    // @codeCoverageIgnoreEnd
}
