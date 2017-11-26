<?php

namespace Reinfi\GoAop\ExpressiveMiddleware\Kernel\Factory;

use Go\Core\AspectKernel;
use Reinfi\GoAop\ExpressiveMiddleware\Kernel\ExpressiveAspectKernel;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware\Kernel\Factory
 */
class AspectKernelFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AspectKernel
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $aspectKernel = ExpressiveAspectKernel::getInstance();
        $aspectKernel->init($container->get('config')['goaop_module']);

        return $aspectKernel;
    }
}
