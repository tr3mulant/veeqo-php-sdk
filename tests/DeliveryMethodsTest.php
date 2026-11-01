<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Data\DeliveryMethods\CreateDeliveryMethodData;
use TremulantTech\Veeqo\Data\DeliveryMethods\UpdateDeliveryMethodData;
use TremulantTech\Veeqo\Requests\DeliveryMethods\CreateDeliveryMethod;
use TremulantTech\Veeqo\Requests\DeliveryMethods\DeleteDeliveryMethod;
use TremulantTech\Veeqo\Requests\DeliveryMethods\GetDeliveryMethod;
use TremulantTech\Veeqo\Requests\DeliveryMethods\ListDeliveryMethods;
use TremulantTech\Veeqo\Requests\DeliveryMethods\UpdateDeliveryMethod;
use TremulantTech\Veeqo\Responses\DeliveryMethod;

it('gets a delivery method as a typed DeliveryMethod', function () {
    $mock = new MockClient([GetDeliveryMethod::class => fixtureResponse('responses/delivery_methods.show')]);

    $deliveryMethod = veeqo($mock)->deliveryMethods()->get(1043);

    expect($deliveryMethod)->toBeInstanceOf(DeliveryMethod::class)
        ->and($deliveryMethod->id)->toBe(1043)
        ->and($deliveryMethod->cost)->toBe('0.0')
        ->and($deliveryMethod->userId)->toBe(1001)
        ->and($deliveryMethod->createdAt->format(DATE_ATOM))->toBe('2020-01-01T00:00:00+00:00');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/delivery_methods/1043'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('lists delivery methods lazily across pages', function () {
    $queries = [];
    $mock = new MockClient([ListDeliveryMethods::class => function (PendingRequest $request) use (&$queries) {
        $queries[] = $request->createPsrRequest()->getUri()->getQuery();
        $deliveryMethods = json_decode(fixtureResponse('responses/delivery_methods.list')->body(), true);

        return MockResponse::make($request->query()->get('page') === 1 ? array_slice(array_merge(...array_fill(0, 17, $deliveryMethods)), 0, 100) : $deliveryMethods);
    }]);

    $deliveryMethods = veeqo($mock)->deliveryMethods()->list();

    expect($queries)->toBe([]);
    $deliveryMethods = iterator_to_array($deliveryMethods, false);

    expect($deliveryMethods)->toHaveCount(106)->each->toBeInstanceOf(DeliveryMethod::class)
        ->and($queries)->toBe(['page=1&page_size=100', 'page=2&page_size=100']);
});

it('creates a delivery method, sending a flat body with money as a string', function () {
    $mock = new MockClient([CreateDeliveryMethod::class => fixtureResponse('docs/create-a-delivery-method')]);

    $deliveryMethod = veeqo($mock)->deliveryMethods()->create(new CreateDeliveryMethodData(name: 'Delivery Method #3', cost: '3.0'));

    expect($deliveryMethod)->toBeInstanceOf(DeliveryMethod::class)
        ->and($deliveryMethod->id)->toBe(123458);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/delivery_methods'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"name":"Delivery Method #3","cost":"3.0"}');
});

it('updates a delivery method, dropping nulls', function () {
    $mock = new MockClient([UpdateDeliveryMethod::class => fixtureResponse('docs/update-delivery-method-detail')]);

    $deliveryMethod = veeqo($mock)->deliveryMethods()->update(171785, new UpdateDeliveryMethodData(cost: '30.0'));

    expect($deliveryMethod)->toBeInstanceOf(DeliveryMethod::class)
        ->and($deliveryMethod->cost)->toBe('30.0');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/delivery_methods/171785'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"cost":"30.0"}');
});

it('deletes a delivery method', function () {
    $mock = new MockClient([DeleteDeliveryMethod::class => MockResponse::make(status: 200)]);

    $result = veeqo($mock)->deliveryMethods()->delete(1043);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/delivery_methods/1043'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});
