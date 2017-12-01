<?php

namespace Go\Zend\Expressive\Middleware;

use Go\Core\AspectContainer;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        foreach ($this->aspects as $aspectName) {
            $aspect = $this->container->get($aspectName);
            $this->aspectContainer->registerAspect($aspect);
        }

        return $delegate->process($request);
    }
}