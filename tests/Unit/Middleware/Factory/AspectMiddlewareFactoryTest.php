<?php

namespace Go\Zend\Expressive\Tests\Unit\Middleware\Factory;

use Go\Core\AspectContainer;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Go\Zend\Expressive\Middleware\AspectMiddleware;
use Go\Zend\Expressive\Middleware\Factory\AspectMiddlewareFactory;

/**
 * @package Go\Zend\Expressive\Tests\Unit\Middleware\Factory
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