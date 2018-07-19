<?php

declare(strict_types=1);

namespace Lamoda\TacticianRateLimitBundle;

use Lamoda\TacticianRateLimitBundle\DependencyInjection\Compiler\RateLimitProviderConfigurationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class LamodaTacticianRateLimitBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RateLimitProviderConfigurationCompilerPass(
            'lamoda_tactician_rate_limit.provider',
            'lamoda_tactician_rate_limit.provider'
        ));
    }
}
