<?php

declare(strict_types=1);

namespace Lamoda\TacticianRateLimitBundle\DependencyInjection;

use Lamoda\Tactician\RateLimit\Adapter\Stiphle\StiphleRateLimiterAdapter;
use Psr\Log\NullLogger;
use Stiphle\Throttle\ThrottleInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class LamodaTacticianRateLimitExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->configureLogging($mergedConfig, $container);
        $this->configureRateLimiter($mergedConfig, $container);
    }

    private function configureLogging(array $mergedConfig, ContainerBuilder $container): void
    {
        $logger = null;

        if (!$mergedConfig['logging']['enabled']) {
            $logger = new Definition(NullLogger::class);
        }

        if (null === $logger) {
            $logger = $mergedConfig['logging']['service'];
        }

        if ($logger instanceof Definition) {
            $container->setDefinition('lamoda_tactician_rate_limit.logger', $logger);
        }

        if (is_string($logger)) {
            $container->setAlias('lamoda_tactician_rate_limit.logger', $logger);
        }
    }

    private function configureRateLimiter($mergedConfig, ContainerBuilder $container): void
    {
        $rateLimiter = null;

        if ($mergedConfig['rate_limiter']['stiphle']['enabled']) {
            $this->assertStiphle();
            $rateLimiter = new Definition(StiphleRateLimiterAdapter::class, [
                new Reference($mergedConfig['rate_limiter']['stiphle']['service']),
            ]);
        }

        if (null === $rateLimiter) {
            throw new RuntimeException('No rate_limiter is enabled');
        }

        $container->setDefinition('lamoda_tactician_rate_limit.rate_limiter', $rateLimiter);
    }

    private function assertStiphle(): void
    {
        if (!interface_exists(ThrottleInterface::class)) {
            throw new RuntimeException(
                'Can not use davedevelopment/stiphle as a rate limiter because this library is not installed'
            );
        }
    }
}
