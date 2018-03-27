<?php

namespace Go\Zend\Expressive\Tests\Unit\Middleware;

use Go\Core\AspectContainer;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Go\Zend\Expressive\Middleware\AspectMiddleware;
use Go\Zend\Expressive\Tests\Aspect\TestAspect;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @package Go\Zend\Expressive\Tests\Unit\Middleware
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
        $handler
            ->handle(Argument::type(ServerRequestInterface::class))
            ->willReturn($this->prophesize(ResponseInterface::class)->reveal());

        $middleware->process(
            $request->reveal(),
            $handler->reveal()
        );
    }
}