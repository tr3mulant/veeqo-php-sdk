<?php

namespace TremulantTech\Veeqo;

use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;

// Only loaded by Laravel via package auto-discovery; plain PHP users never touch this class.
class VeeqoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/veeqo.php', 'veeqo');

        // Never bind a connector with an empty credential; Appstore apps call Veeqo::withAccessToken() themselves (ADR 0005).
        $apiKey = $this->app->make(Repository::class)->get('veeqo.api_key');

        if (is_string($apiKey) && $apiKey !== '') {
            $this->app->singleton(Veeqo::class, fn () => Veeqo::withApiKey($apiKey));
        }
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/veeqo.php' => config_path('veeqo.php')], 'veeqo-config');
    }
}
