<?php

namespace Reinfi\GoAop\ExpressiveMiddleware\Middleware\Factory;

use Go\Core\AspectContainer;
use Reinfi\GoAop\ExpressiveMiddleware\Middleware\AspectMiddleware;
use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware\Middleware\Factory
 */
class AspectMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return AspectMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        $aspectContainer = $container->get(AspectContainer::class);
        $aspects = $container->get('config')['goaop_aspect'];

        return new AspectMiddleware(
            $container,
            $aspectContainer,
            $aspects
        );
    }
}