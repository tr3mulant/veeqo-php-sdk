<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Data\Suppliers\CreateSupplierData;
use TremulantTech\Veeqo\Data\Suppliers\UpdateSupplierData;
use TremulantTech\Veeqo\Requests\Suppliers\CreateSupplier;
use TremulantTech\Veeqo\Requests\Suppliers\DeleteSupplier;
use TremulantTech\Veeqo\Requests\Suppliers\GetSupplier;
use TremulantTech\Veeqo\Requests\Suppliers\ListSuppliers;
use TremulantTech\Veeqo\Requests\Suppliers\UpdateSupplier;
use TremulantTech\Veeqo\Responses\Supplier;

it('gets a supplier as a typed Supplier, keeping money as a string', function () {
    $mock = new MockClient([GetSupplier::class => fixtureResponse('docs/view-a-supplier-detail')]);

    $supplier = veeqo($mock)->suppliers()->get(12345);

    expect($supplier)->toBeInstanceOf(Supplier::class)
        ->and($supplier->id)->toBe(12345)
        ->and($supplier->name)->toBe('Default supplier')
        ->and($supplier->creditLimit)->toBe('0')
        ->and($supplier->salesPhoneNumber)->toBeNull()
        ->and($supplier->createdAt?->format(DATE_ATOM))->toBe('2023-09-20T14:27:46+00:00');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/suppliers/12345'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('lists suppliers lazily across pages', function () {
    $queries = [];
    $mock = new MockClient([ListSuppliers::class => function (PendingRequest $request) use (&$queries) {
        $queries[] = $request->createPsrRequest()->getUri()->getQuery();
        $suppliers = json_decode(fixtureResponse('docs/list-all-suppliers')->body(), true);

        return MockResponse::make($request->query()->get('page') === 1 ? array_merge(...array_fill(0, 100, $suppliers)) : $suppliers);
    }]);

    $suppliers = veeqo($mock)->suppliers()->list();

    expect($queries)->toBe([]);
    $suppliers = iterator_to_array($suppliers, false);

    expect($suppliers)->toHaveCount(101)->each->toBeInstanceOf(Supplier::class)
        ->and($queries)->toBe(['page=1&page_size=100', 'page=2&page_size=100']);
});

it('creates a supplier, sending a flat body with money as a string and nulls dropped', function () {
    $mock = new MockClient([CreateSupplier::class => fixtureResponse('docs/create-a-new-supplier')]);

    $supplier = veeqo($mock)->suppliers()->create(new CreateSupplierData(
        name: 'Default supplier',
        addressLine1: '221 High St',
        country: 'GB',
        currencyCode: 'gbp',
        creditLimit: '250.00',
    ));

    expect($supplier)->toBeInstanceOf(Supplier::class)
        ->and($supplier->id)->toBe(12345);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/suppliers'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"name":"Default supplier","address_line_1":"221 High St","country":"GB","currency_code":"gbp","credit_limit":"250.00"}');
});

it('updates a supplier unenveloped, dropping nulls', function () {
    // Veeqo documents a 200 body for this operation but publishes no example, so the detail example stands in.
    $mock = new MockClient([UpdateSupplier::class => fixtureResponse('docs/view-a-supplier-detail')]);

    $supplier = veeqo($mock)->suppliers()->update(12345, new UpdateSupplierData(addressLine2: 'Floor 4'));

    expect($supplier)->toBeInstanceOf(Supplier::class)
        ->and($supplier->addressLine2)->toBe('Floor 4');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/suppliers/12345'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"address_line_2":"Floor 4"}');
});

it('deletes a supplier', function () {
    $mock = new MockClient([DeleteSupplier::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->suppliers()->delete(12345);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/suppliers/12345'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});
