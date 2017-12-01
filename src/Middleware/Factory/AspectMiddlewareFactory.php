<?php

namespace Go\Zend\Expressive\Middleware\Factory;

use Go\Core\AspectContainer;
use Go\Zend\Expressive\Middleware\AspectMiddleware;
use Psr\Container\ContainerInterface;

/**
 * @package Go\Zend\Expressive\Middleware\Factory
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