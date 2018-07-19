<?php

declare(strict_types=1);

namespace Lamoda\TacticianRateLimitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder();

        $root = $builder->root('lamoda_tactician_rate_limit');

        $root
            ->children()
                ->arrayNode('logging')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('service')
                            ->defaultValue('logger')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('rate_limiter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('stiphle')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('service')
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
