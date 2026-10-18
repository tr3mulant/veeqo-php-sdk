<?php

use Illuminate\Support\Facades\Cache;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Tests\Laravel\TestCase;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Veeqo;
use TremulantTech\Veeqo\VeeqoServiceProvider;

uses(TestCase::class);

it('binds a connector built from the configured api key', function () {
    registerWithApiKey('laravel-key');
    $mock = new MockClient([MockResponse::make(['id' => 7])]);

    app(Veeqo::class)->withMockClient($mock)->send(new GetOrder(7));

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->headers()->get('x-api-key') === 'laravel-key'
        && $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/7');
});

it('binds the connector as a singleton', function () {
    registerWithApiKey('laravel-key');

    expect(app(Veeqo::class))->toBe(app(Veeqo::class));
});

it('does not bind a connector without an api key', function (?string $apiKey) {
    registerWithApiKey($apiKey);

    expect(app()->bound(Veeqo::class))->toBeFalse();
})->with(['null' => null, 'empty' => '']);

it('rate limits the singleton through the configured cache store', function () {
    registerWithApiKey('laravel-key', 'array');
    app(Veeqo::class)->withMockClient(new MockClient([MockResponse::make(['id' => 7])]))->send(new GetOrder(7));

    expect(Cache::store('array')->get('veeqo:'.hash('sha256', 'laravel-key').':25_every_5'))->toBeString();
});

function registerWithApiKey(?string $apiKey, ?string $rateLimitStore = null): void
{
    config(['veeqo.api_key' => $apiKey, 'veeqo.rate_limit_store' => $rateLimitStore]);
    app()->forgetInstance(Veeqo::class);
    app()->offsetUnset(Veeqo::class);
    app()->register(VeeqoServiceProvider::class, force: true);
}
