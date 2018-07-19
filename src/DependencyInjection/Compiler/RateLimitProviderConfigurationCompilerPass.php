<?php

declare(strict_types=1);

namespace Lamoda\TacticianRateLimitBundle\DependencyInjection\Compiler;

use Lamoda\Tactician\RateLimit\RateLimiter\FirstMatchedRateLimitProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class RateLimitProviderConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $tagName;
    /**
     * @var string
     */
    private $serviceName;

    public function __construct(string $tagName, string $serviceName)
    {
        $this->tagName = $tagName;
        $this->serviceName = $serviceName;
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $services = array();

        foreach ($container->findTaggedServiceIds($this->tagName, true) as $serviceId => $attributes) {
            $priority = $attributes[0]['priority'] ?? 0;
            $services[$priority][] = new Reference($serviceId);
        }

        if ($services) {
            krsort($services);
            $services = array_merge(...$services);
        }

        if (1 == count($services)) {
            $container->setAlias(
                $this->serviceName,
                (string) reset($services)
            );
        } else {
            $container->setDefinition(
                $this->serviceName,
                new Definition(FirstMatchedRateLimitProvider::class, $services)
            );
        }
    }
}
