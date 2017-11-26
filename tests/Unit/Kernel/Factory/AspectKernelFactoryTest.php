<?php

namespace Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit\Kernel\Factory;

use Go\Core\AspectKernel;
use Interop\Container\ContainerInterface;
use Reinfi\GoAop\ExpressiveMiddleware\Kernel\Factory\AspectKernelFactory;
use PHPUnit\Framework\TestCase;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit\Kernel\Factory
 */
class AspectKernelFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesKernel()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn(['goaop_module' => require __DIR__ . '/../../../resources/goaop_module.php'])
            ->shouldBeCalled();

        $factory = new AspectKernelFactory();

        $instance = $factory($container->reveal(), AspectKernel::class);

        $this->assertInstanceOf(
            AspectKernel::class,
            $instance,
            'factory should return an instance of ' . AspectKernel::class
        );
    }
}