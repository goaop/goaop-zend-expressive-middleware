<?php

namespace Go\Zend\Expressive\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Go\Zend\Expressive\ConfigProvider;

/**
 * @package Go\Zend\Expressive\Tests\Unit
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