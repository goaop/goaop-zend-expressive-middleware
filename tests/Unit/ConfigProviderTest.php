<?php

namespace Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Reinfi\GoAop\ExpressiveMiddleware\ConfigProvider;

/**
 * @package Reinfi\GoAop\ExpressiveMiddleware\Tests\Unit
 */
class ConfigProviderTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsConfig()
    {
        $provider = new ConfigProvider();

        $this->assertInternalType(
            'array',
            $provider(),
            'returned config should be of type array'
        );
    }
}