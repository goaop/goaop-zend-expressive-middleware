<?php

namespace Reinfi\GoAop\ExpressiveMiddleware\Middleware;

use Go\Core\AspectContainer;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware\Middleware
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
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) {
        foreach ($this->aspects as $aspectName) {
            $aspect = $this->container->get($aspectName);
            $this->aspectContainer->registerAspect($aspect);
        }

        return $handler->handle($request);
    }
}