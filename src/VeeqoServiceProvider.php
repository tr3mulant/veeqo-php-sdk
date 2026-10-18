<?php

namespace TremulantTech\Veeqo;

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;

// Only loaded by Laravel via package auto-discovery; plain PHP users never touch this class.
class VeeqoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/veeqo.php', 'veeqo');

        // Never bind a connector with an empty credential; Appstore apps call Veeqo::withAccessToken() themselves (ADR 0005).
        $config = $this->app->make(Repository::class);
        $apiKey = $config->get('veeqo.api_key');

        if (is_string($apiKey) && $apiKey !== '') {
            $this->app->singleton(Veeqo::class, function () use ($config, $apiKey) {
                // Names a cache store to share the rate limit window across workers (ADR 0009).
                $store = $config->get('veeqo.rate_limit_store');

                return Veeqo::withApiKey($apiKey, rateLimitStore: is_string($store) && $store !== ''
                    ? new LaravelCacheStore($this->app->make(CacheManager::class)->store($store))
                    : null);
            });
        }
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/veeqo.php' => config_path('veeqo.php')], 'veeqo-config');
    }
}
