<?php

namespace Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit\Factory;

use Go\Core\AspectContainer;
use Go\Core\AspectKernel;
use Interop\Container\ContainerInterface;
use Reinfi\GoAop\ExpressiveMiddleware\Factory\AspectContainerFactory;
use PHPUnit\Framework\TestCase;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit\Factory
 */
class AspectContainerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesAspectContainerOnInvoke()
    {
        $aspectContainer = $this->prophesize(AspectContainer::class);

        $aspectKernel = $this->prophesize(AspectKernel::class);
        $aspectKernel->getContainer()
            ->willReturn($aspectContainer->reveal())
            ->shouldBeCalled();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(AspectKernel::class)
            ->willReturn($aspectKernel->reveal())
            ->shouldBeCalled();

        $factory = new AspectContainerFactory();

        $instance = $factory($container->reveal(), AspectContainer::class);

        $this->assertInstanceOf(
            AspectContainer::class,
            $instance,
            'factory should return an instance of ' . AspectContainer::class
        );
    }
}