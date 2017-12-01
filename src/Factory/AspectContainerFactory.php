<?php

namespace Go\Zend\Expressive\Factory;

use Go\Core\AspectContainer;
use Go\Core\AspectKernel;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * @package Go\Zend\Expressive\Factory
 */
class AspectContainerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AspectContainer
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $kernel = $container->get(AspectKernel::class);

        return $kernel->getContainer();
    }
}
