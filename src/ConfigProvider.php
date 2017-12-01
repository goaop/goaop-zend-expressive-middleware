<?php

namespace Go\Zend\Expressive;

/**
 * @package Go\Zend\Expressive
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