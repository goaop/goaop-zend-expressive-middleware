<?php

namespace Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit\Middleware\Factory;

use Go\Core\AspectContainer;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\GoAop\ExpressiveMiddleware\Middleware\AspectMiddleware;
use Reinfi\GoAop\ExpressiveMiddleware\Middleware\Factory\AspectMiddlewareFactory;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit\Middleware\Factory
 */
class AspectMiddlewareFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesAspectMiddleware()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(AspectContainer::class)
            ->willReturn(
                $this->prophesize(AspectContainer::class)->reveal()
            );

        $container->get('config')
            ->willReturn(['goaop_aspect' => []]);

        $factory = new AspectMiddlewareFactory();

        $this->assertInstanceOf(
            AspectMiddleware::class,
            $factory($container->reveal(), AspectMiddleware::class)
        );
    }
}