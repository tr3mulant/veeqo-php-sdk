<?php

namespace TremulantTech\Veeqo;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

// Only loaded by Laravel via package auto-discovery; plain PHP users never touch this class.
class VeeqoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/veeqo.php', 'veeqo');

        $this->app->singleton(Veeqo::class, function (Application $app) {
            $config = $app->make(Repository::class);
            $apiKey = $config->get('veeqo.api_key');

            return new Veeqo(
                apiKey: is_string($apiKey) ? $apiKey : '',
                baseUrl: $config->string('veeqo.base_url'),
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/veeqo.php' => config_path('veeqo.php')], 'veeqo-config');
    }
}
