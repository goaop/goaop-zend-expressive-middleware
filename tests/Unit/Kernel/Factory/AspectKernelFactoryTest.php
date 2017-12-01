<?php

namespace Go\Zend\Expressive\Tests\Unit\Kernel\Factory;

use Go\Core\AspectKernel;
use Interop\Container\ContainerInterface;
use Go\Zend\Expressive\Kernel\Factory\AspectKernelFactory;
use PHPUnit\Framework\TestCase;

/**
 * @package Go\Zend\Expressive\Tests\Unit\Kernel\Factory
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