# Lamoda Tactician rate limit middleware bundle
Utility wrapper for https://github.com:lamoda/tactician-rate-limit

## Installation

Usage is as simple as 

```bash
composer require lamoda/tactician-rate-limit-bundle
# Currently this bundle supports only https://github.com/davedevelopment/stiphle rate limiter, so install it:
composer require davedevelopment/stiphle
```

```php
// Kernel

public function registerBundles()
{
    // ...
    $bundles[] = new \Lamoda\TacticianRateLimitBundle\LamodaTacticianRateLimitBundle();
    // ...
}
```

```yaml
# config.yml
lamoda_tactician_rate_limit:
    logging:
        service: logger # to use logging
    rate_limiter:
        stiphle:
            service: stiphle # point to stiphle service
            
services:
    stiphle:
        class: Stiphle\Throttle\LeakyBucket
```
