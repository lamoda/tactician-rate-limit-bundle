<?php

declare(strict_types=1);

namespace Lamoda\TacticianRateLimitBundle\Tests\Functional\Fixture;

use Lamoda\TacticianRateLimitBundle\LamodaTacticianRateLimitBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    /**
     * @var array
     */
    private $extraConfigs;

    public function __construct(string $environment, bool $debug, array $extraConfigs)
    {
        $environment .= crc32(serialize($this->extraConfigs));

        parent::__construct($environment, $debug);
        $this->extraConfigs = $extraConfigs;
    }

    /** {@inheritdoc} */
    public function registerBundles()
    {
        return [
            new LamodaTacticianRateLimitBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yml');
        foreach ($this->extraConfigs as $config) {
            $loader->load(__DIR__ . '/' . $config);
        }
    }

    public function getCacheDir()
    {
        return __DIR__ . '/../../../build/cache/' . $this->environment;
    }

    public function getLogDir()
    {
        return __DIR__ . '/../../../build/logs/' . $this->environment;
    }
}
