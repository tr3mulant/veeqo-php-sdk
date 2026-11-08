<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Data\Warehouses\CreateWarehouseData;
use TremulantTech\Veeqo\Data\Warehouses\CutOffTimesData;
use TremulantTech\Veeqo\Data\Warehouses\CutOffTimesDefaultData;
use TremulantTech\Veeqo\Data\Warehouses\UpdateWarehouseData;
use TremulantTech\Veeqo\Requests\Warehouses\CreateWarehouse;
use TremulantTech\Veeqo\Requests\Warehouses\DeleteWarehouse;
use TremulantTech\Veeqo\Requests\Warehouses\GetWarehouse;
use TremulantTech\Veeqo\Requests\Warehouses\ListWarehouses;
use TremulantTech\Veeqo\Requests\Warehouses\UpdateWarehouse;
use TremulantTech\Veeqo\Responses\Warehouse;

it('gets a warehouse as a typed Warehouse', function () {
    $mock = new MockClient([GetWarehouse::class => fixtureResponse('responses/warehouses.show')]);

    $warehouse = veeqo($mock)->warehouses()->get(1003);

    expect($warehouse)->toBeInstanceOf(Warehouse::class)
        ->and($warehouse->id)->toBe(1003)
        ->and($warehouse->region)->toBe('WA')
        ->and($warehouse->cutOffTimesEnabled)->toBeFalse()
        ->and($warehouse->createdAt?->format(DATE_ATOM))->toBe('2020-01-01T00:00:00+00:00');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/warehouses/1003'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('lists warehouses lazily across pages, sending the external id filter', function () {
    $queries = [];
    $mock = new MockClient([ListWarehouses::class => function (PendingRequest $request) use (&$queries) {
        $queries[] = $request->createPsrRequest()->getUri()->getQuery();
        $warehouses = json_decode(fixtureResponse('responses/warehouses.list')->body(), true);

        return MockResponse::make($request->query()->get('page') === 1 ? array_merge(...array_fill(0, 50, $warehouses)) : $warehouses);
    }]);

    $warehouses = veeqo($mock)->warehouses()->list(externalId: 'WH-1');

    expect($queries)->toBe([]);
    $warehouses = iterator_to_array($warehouses, false);

    expect($warehouses)->toHaveCount(102)->each->toBeInstanceOf(Warehouse::class)
        ->and($queries)->toBe([
            'external_id=WH-1&page=1&page_size=100',
            'external_id=WH-1&page=2&page_size=100',
        ]);
});

it('creates a warehouse, sending a flat body with nested cut-off times and nulls dropped', function () {
    $mock = new MockClient([CreateWarehouse::class => fixtureResponse('docs/create-a-warehouse')]);

    $warehouse = veeqo($mock)->warehouses()->create(new CreateWarehouseData(
        name: 'My Warehouse',
        addressLine1: '221 High Street',
        city: 'Swansea',
        country: 'GB',
        postCode: 'SA1 1NW',
        cutOffTimesEnabled: true,
        cutOffTimes: new CutOffTimesData(default: new CutOffTimesDefaultData(monday: '18:00', saturday: '')),
    ));

    expect($warehouse)->toBeInstanceOf(Warehouse::class)
        ->and($warehouse->id)->toBe(123)
        ->and($warehouse->cutOffTimes?->default?->monday)->toBe('18:00')
        ->and($warehouse->cutOffTimes?->default?->sunday)->toBe('');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/warehouses'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"name":"My Warehouse","address_line_1":"221 High Street","city":"Swansea","country":"GB","post_code":"SA1 1NW","cut_off_times_enabled":true,"cut_off_times":{"default":{"monday":"18:00","saturday":""}}}');
});

it('updates a warehouse unenveloped, returning nothing though the 204 carries a body', function () {
    $mock = new MockClient([UpdateWarehouse::class => fixtureResponse('docs/update-warehouse-detail')]);

    $result = veeqo($mock)->warehouses()->update(123, new UpdateWarehouseData(addressLine2: 'Floor 4', cutOffTimesEnabled: false));

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/warehouses/123'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"address_line_2":"Floor 4","cut_off_times_enabled":false}');
});

it('deletes a warehouse', function () {
    $mock = new MockClient([DeleteWarehouse::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->warehouses()->delete(123);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/warehouses/123'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});

it('lists warehouses without a filter', function () {
    $mock = new MockClient([ListWarehouses::class => fixtureResponse('responses/warehouses.list')]);

    expect(iterator_to_array(veeqo($mock)->warehouses()->list(), false))->toHaveCount(2);
    $mock->assertSent(fn ($request, $response) => $response->getPsrRequest()->getUri()->getQuery() === 'page=1&page_size=100');
});
