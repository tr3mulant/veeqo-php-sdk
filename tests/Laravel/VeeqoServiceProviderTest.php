<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Tests\Laravel\TestCase;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Veeqo;
use TremulantTech\Veeqo\VeeqoServiceProvider;

uses(TestCase::class);

function registerWithApiKey(?string $apiKey): void
{
    config(['veeqo.api_key' => $apiKey]);
    app()->forgetInstance(Veeqo::class);
    app()->offsetUnset(Veeqo::class);
    app()->register(VeeqoServiceProvider::class, force: true);
}

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
