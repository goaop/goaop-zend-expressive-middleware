<?php

namespace Reinfi\GoAop\ExpressiveMiddleware\Tests\Integration\Middleware;

use Go\Core\AspectContainer;
use PHPUnit\Framework\TestCase;
use Reinfi\GoAop\ExpressiveMiddleware\ConfigProvider;
use Reinfi\GoAop\ExpressiveMiddleware\Middleware\AspectMiddleware;
use Reinfi\GoAop\ExpressiveMiddleware\Tests\Aspect\TestAspect;
use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Router\RouterInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware\Tests\Integration\Middleware
 */
class AspectMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function itRegistersAspect()
    {
        $config = (
            new ConfigAggregator(
                [
                    ConfigProvider::class,
                    new ArrayProvider(
                        [
                            'dependencies' => [
                                'factories' => [
                                    Application::class => ApplicationFactory::class,
                                    RouterInterface::class => function () {
                                        return $this->prophesize(RouterInterface::class)->reveal();
                                    },
                                    EmitterInterface::class => function() {
                                        return $this->prophesize(EmitterInterface::class)->reveal();
                                    },
                                    TestAspect::class => InvokableFactory::class,
                                ],
                            ],
                        ]
                    ),
                    new ArrayProvider(
                        [
                            'goaop_module' => require __DIR__ . '/../../resources/goaop_module.php',
                            'goaop_aspect' => [
                                TestAspect::class,
                            ],
                        ]
                    ),
                ]
            )
        )->getMergedConfig();

        $container = new ServiceManager();
        (new Config($config['dependencies']))->configureServiceManager(
            $container
        );

        $container->setService('config', $config);

        /** @var Application $app */
        $app = $container->get(Application::class);

        $app->pipe(AspectMiddleware::class);

        $app->run();

        /** @var AspectContainer $aspectContainer */
        $aspectContainer = $container->get(AspectContainer::class);

        $aspect = $aspectContainer->getAspect(TestAspect::class);

        $this->assertInstanceOf(
            TestAspect::class,
            $aspect,
            'aspect should be instance of registered test aspect class'
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfAspectIsNotRegistered()
    {
        $this->expectException(ServiceNotFoundException::class);

        $config = (
        new ConfigAggregator(
            [
                ConfigProvider::class,
                new ArrayProvider(
                    [
                        'dependencies' => [
                            'factories' => [
                                Application::class => ApplicationFactory::class,
                                RouterInterface::class => function () {
                                    return $this->prophesize(RouterInterface::class)->reveal();
                                },
                                EmitterInterface::class => function() {
                                    return $this->prophesize(EmitterInterface::class)->reveal();
                                },
                            ],
                        ],
                    ]
                ),
                new ArrayProvider(
                    [
                        'goaop_module' => require __DIR__ . '/../../resources/goaop_module.php',
                        'goaop_aspect' => [
                            TestAspect::class,
                        ],
                    ]
                ),
            ]
        )
        )->getMergedConfig();

        $container = new ServiceManager();
        (new Config($config['dependencies']))->configureServiceManager(
            $container
        );

        $container->setService('config', $config);

        /** @var Application $app */
        $app = $container->get(Application::class);

        $app->pipe(AspectMiddleware::class);

        $app->run();
    }
}