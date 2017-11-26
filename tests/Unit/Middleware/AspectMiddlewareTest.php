<?php

namespace Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit\Middleware;

use Go\Core\AspectContainer;
use Interop\Http\Server\RequestHandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reinfi\GoAop\ExpressiveMiddleware\Middleware\AspectMiddleware;
use Reinfi\GoAop\ExpressiveMiddleware\Tests\Aspect\TestAspect;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit\Middleware
 */
class AspectMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function itRegistersAspects()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $aspect = new TestAspect();
        $container
            ->get(TestAspect::class)
            ->willReturn($aspect)
            ->shouldBeCalled();

        $aspectContainer = $this->prophesize(AspectContainer::class);
        $aspectContainer
            ->registerAspect($aspect)
            ->shouldBeCalled();

        $aspects = [
            TestAspect::class
        ];

        $middleware = new AspectMiddleware(
            $container->reveal(),
            $aspectContainer->reveal(),
            $aspects
        );

        $request = $this->prophesize(ServerRequestInterface::class);
        $handler = $this->prophesize(RequestHandlerInterface::class);

        $middleware->process(
            $request->reveal(),
            $handler->reveal()
        );
    }
}