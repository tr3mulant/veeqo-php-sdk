<?php

namespace IronGate\Veeqo;

use Illuminate\Support\ServiceProvider;

// Only loaded by Laravel via package auto-discovery; plain PHP users never touch this class.
class VeeqoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/veeqo.php', 'veeqo');

        $this->app->singleton(Veeqo::class, fn ($app) => new Veeqo(
            apiKey: (string) $app['config']['veeqo.api_key'],
            baseUrl: $app['config']['veeqo.base_url'],
        ));
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/veeqo.php' => config_path('veeqo.php')], 'veeqo-config');
    }
}
