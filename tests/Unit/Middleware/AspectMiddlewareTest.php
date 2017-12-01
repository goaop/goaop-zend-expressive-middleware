<?php

namespace Go\Zend\Expressive\Tests\Unit\Middleware;

use Go\Core\AspectContainer;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Go\Zend\Expressive\Middleware\AspectMiddleware;
use Go\Zend\Expressive\Tests\Aspect\TestAspect;

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
        $delegate = $this->prophesize(DelegateInterface::class);

        $middleware->process(
            $request->reveal(),
            $delegate->reveal()
        );
    }
}