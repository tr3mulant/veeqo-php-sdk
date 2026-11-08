<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Data\ProductProperties\CreateProductPropertyData;
use TremulantTech\Veeqo\Data\ProductPropertySpecifics\UpdateProductPropertySpecificData;
use TremulantTech\Veeqo\Requests\ProductProperties\CreateProductProperty;
use TremulantTech\Veeqo\Requests\ProductPropertySpecifics\DeleteProductPropertySpecific;
use TremulantTech\Veeqo\Requests\ProductPropertySpecifics\GetProductPropertySpecific;
use TremulantTech\Veeqo\Requests\ProductPropertySpecifics\UpdateProductPropertySpecific;
use TremulantTech\Veeqo\Responses\ProductProperty;
use TremulantTech\Veeqo\Responses\ProductPropertySpecific;
use TremulantTech\Veeqo\Responses\ProductPropertySpecificSummary;

it('creates a product property, sending a flat body', function () {
    $mock = new MockClient([CreateProductProperty::class => fixtureResponse('docs/create-a-new-property')]);

    $property = veeqo($mock)->productProperties()->create(new CreateProductPropertyData(name: 'Some Awesome Property'));

    expect($property)->toBeInstanceOf(ProductProperty::class)
        ->and($property->id)->toBe(135498)
        ->and($property->name)->toBe('Some Awesome Property');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/product_properties'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"name":"Some Awesome Property"}');
});

it('gets a product property specific', function () {
    $mock = new MockClient([GetProductPropertySpecific::class => fixtureResponse('docs/view-properties')]);

    $specific = veeqo($mock)->productPropertySpecifics()->get(productId: 132663, id: 24352077);

    expect($specific)->toBeInstanceOf(ProductPropertySpecific::class)
        ->and($specific->id)->toBe(24352077)
        ->and($specific->value)->toBe('Blue');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/products/132663/product_property_specifics/24352077'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('updates a product property specific, sending a flat body and dropping nulls', function () {
    $mock = new MockClient([UpdateProductPropertySpecific::class => fixtureResponse('docs/update-property-detail')]);

    $specific = veeqo($mock)->productPropertySpecifics()->update(productId: 132663, id: 24352077, data: new UpdateProductPropertySpecificData(value: '13'));

    expect($specific)->toBeInstanceOf(ProductPropertySpecificSummary::class)
        ->and($specific->value)->toBe('13');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/products/132663/product_property_specifics/24352077'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"value":"13"}');
});

it('deletes a product property specific', function () {
    $mock = new MockClient([DeleteProductPropertySpecific::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->productPropertySpecifics()->delete(productId: 132663, id: 24352077);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/products/132663/product_property_specifics/24352077'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});
