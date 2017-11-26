<?php

namespace Reinfi\GoAop\ExpressiveMiddleware;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return require __DIR__ . '/../config/config.php';
    }
}