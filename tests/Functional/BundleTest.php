<?php

namespace Lamoda\TacticianRateLimitBundle\Tests\Functional;

use Lamoda\Tactician\RateLimit\RateLimitMiddleware;
use Lamoda\TacticianRateLimitBundle\Tests\Functional\Fixture\TestKernel;
use PHPUnit\Framework\TestCase;

class BundleTest extends TestCase
{
    /**
     * @dataProvider dataMiddlewareInitialization
     */
    public function testMiddlewareInitialization(array $configs)
    {
        $kernel = $this->loadKernel($configs);
        $container = $kernel->getContainer();

        $this->assertTrue($container->has('test.lamoda_tactician_rate_limit.middleware'));

        $middleware = $container->get('test.lamoda_tactician_rate_limit.middleware');

        $this->assertInstanceOf(RateLimitMiddleware::class, $middleware);
    }

    public function dataMiddlewareInitialization(): array
    {
        return [
            [
                ['single_provider.yml'],
            ],
            [
                ['multiple_providers.yml'],
            ],
        ];
    }

    private function loadKernel(array $extraConfigs): TestKernel
    {
        $kernel = new TestKernel('test', false, $extraConfigs);
        $kernel->boot();

        return $kernel;
    }
}
