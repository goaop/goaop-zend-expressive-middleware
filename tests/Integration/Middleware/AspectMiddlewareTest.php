<?php

namespace Go\Zend\Expressive\Tests\Integration\Middleware;

use Go\Core\AspectContainer;
use PHPUnit\Framework\TestCase;
use Go\Zend\Expressive\ConfigProvider as GoExpressiveConfigProvider;
use Go\Zend\Expressive\Middleware\AspectMiddleware;
use Go\Zend\Expressive\Tests\Aspect\TestAspect;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\Expressive\Application;
use Zend\Expressive\ConfigProvider as ExpressiveConfigProvider;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Router\ConfigProvider as ExpressiveRouterConfigProvider;
use Zend\Expressive\Router\RouterInterface;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * @package Go\Zend\Expressive\Tests\Integration\Middleware
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
                    ExpressiveConfigProvider::class,
                    ExpressiveRouterConfigProvider::class,
                    GoExpressiveConfigProvider::class,
                    new ArrayProvider(
                        [
                            'dependencies' => [
                                'factories' => [
                                    Application::class => ApplicationFactory::class,
                                    RouterInterface::class => function () {
                                        return $this->prophesize(RouterInterface::class)->reveal();
                                    },
                                    EmitterInterface::class => function() {
                                        $emitter = $this->prophesize(EmitterInterface::class);

                                        $emitter->emit(Argument::type(ResponseInterface::class))->willReturn(true);

                                        return $emitter->reveal();
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

        $responseMiddleware = $this->prophesize(MiddlewareInterface::class);
        $responseMiddleware
            ->process(Argument::any(), Argument::any())
            ->willReturn($this->prophesize(ResponseInterface::class)->reveal());
        $app->pipe($responseMiddleware->reveal());

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
                ExpressiveConfigProvider::class,
                ExpressiveRouterConfigProvider::class,
                GoExpressiveConfigProvider::class,
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