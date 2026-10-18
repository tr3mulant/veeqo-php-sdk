<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Tests\Laravel\TestCase;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Veeqo;

uses(TestCase::class);

it('resolves a connector built from Laravel config', function () {
    config(['veeqo.api_key' => 'laravel-key', 'veeqo.base_url' => 'https://example.test']);
    $mock = new MockClient([MockResponse::make(['id' => 7])]);

    app(Veeqo::class)->withMockClient($mock)->send(new GetOrder(7));

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->headers()->get('x-api-key') === 'laravel-key'
        && $response->getPendingRequest()->getUrl() === 'https://example.test/orders/7');
});
