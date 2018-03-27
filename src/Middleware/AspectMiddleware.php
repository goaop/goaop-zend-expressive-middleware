<?php

namespace Go\Zend\Expressive\Middleware;

use Go\Core\AspectContainer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @package Go\Zend\Expressive\Middleware
 */
class AspectMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var AspectContainer
     */
    private $aspectContainer;

    /**
     * @var array
     */
    private $aspects;

    /**
     * @param ContainerInterface $container
     * @param AspectContainer    $aspectContainer
     * @param array              $aspects
     */
    public function __construct(
        ContainerInterface $container,
        AspectContainer $aspectContainer,
        array $aspects
    ) {
        $this->container = $container;
        $this->aspectContainer = $aspectContainer;
        $this->aspects = $aspects;
    }

    /**
     * @inheritdoc
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        foreach ($this->aspects as $aspectName) {
            $aspect = $this->container->get($aspectName);
            $this->aspectContainer->registerAspect($aspect);
        }

        return $handler->handle($request);
    }
}