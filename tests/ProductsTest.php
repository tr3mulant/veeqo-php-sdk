<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Data\Products\CreateProductData;
use TremulantTech\Veeqo\Data\Products\ImageData;
use TremulantTech\Veeqo\Data\Products\ProductVariantData;
use TremulantTech\Veeqo\Data\Products\UpdateProductData;
use TremulantTech\Veeqo\Enums\DimensionsUnit;
use TremulantTech\Veeqo\Requests\Products\CreateProduct;
use TremulantTech\Veeqo\Requests\Products\DeleteProduct;
use TremulantTech\Veeqo\Requests\Products\GetProduct;
use TremulantTech\Veeqo\Requests\Products\ListProducts;
use TremulantTech\Veeqo\Requests\Products\UpdateProduct;
use TremulantTech\Veeqo\Responses\Product;

it('gets a product as a typed Product', function () {
    $mock = new MockClient([GetProduct::class => fixtureResponse('responses/products.show')]);

    $product = veeqo($mock)->products()->get(1342);

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->id)->toBe(1342)
        ->and($product->onHandValue)->toBe('0')
        ->and($product->brand?->id)->toBe(1343)
        ->and($product->sellables[0]->measurementAttributes?->dimensionsUnit)->toBeInstanceOf(DimensionsUnit::class)
        ->and($product->channelProducts[0]->channelSellables[0]->channel?->typeCode)->toBe('amazon_fba')
        ->and($product->createdAt->format(DATE_ATOM))->toBe('2020-01-01T00:00:00+00:00');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/products/1342'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('creates a product, sending the envelope, wire keys and money as strings and dropping nulls', function () {
    $mock = new MockClient([CreateProduct::class => fixtureResponse('docs/create-a-new-product')]);

    $product = veeqo($mock)->products()->create(new CreateProductData(
        title: 'Golden Retriever Dog',
        productBrandId: 7,
        productVariantsAttributes: [new ProductVariantData(title: 'Dog', price: '150.00', skuCode: 'g-dog-1', weightGrams: 10.5)],
        imagesAttributes: [new ImageData(src: 'https://example.com/dog.png', displayPosition: 1)],
    ));

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->id)->toBe(8636420);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/products'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"product":{"title":"Golden Retriever Dog","product_brand_id":7,"product_variants_attributes":[{"title":"Dog","price":"150.00","sku_code":"g-dog-1","weight_grams":10.5}],"images_attributes":[{"src":"https:\/\/example.com\/dog.png","display_position":1}]}}');
});

it('updates a product, sending the envelope and dropping nulls', function () {
    $mock = new MockClient([UpdateProduct::class => fixtureResponse('docs/update-product-detail')]);

    $product = veeqo($mock)->products()->update(8636420, new UpdateProductData(notes: 'Restocked'));

    expect($product)->toBeInstanceOf(Product::class);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/products/8636420'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"product":{"notes":"Restocked"}}');
});

it('deletes a product', function () {
    $mock = new MockClient([DeleteProduct::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->products()->delete(1342);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/products/1342'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});

it('lists products lazily across pages, sending filters with dates as DATE_ATOM in UTC', function () {
    $queries = [];
    $mock = new MockClient([ListProducts::class => function (PendingRequest $request) use (&$queries) {
        $queries[] = $request->createPsrRequest()->getUri()->getQuery();
        $products = json_decode(fixtureResponse('responses/products.list')->body(), true);

        return MockResponse::make($request->query()->get('page') === 1 ? array_merge(...array_fill(0, 10, $products)) : $products);
    }]);
    $at = new DateTimeImmutable('2026-01-31T09:30:00+01:00');

    $products = veeqo($mock)->products()->list(sinceId: 5, warehouseId: 1003, createdAtMin: $at, updatedAtMin: $at, query: 'dog');

    expect($queries)->toBe([]);
    $products = iterator_to_array($products, false);

    $filters = 'since_id=5&warehouse_id=1003&created_at_min=2026-01-31T08%3A30%3A00%2B00%3A00&updated_at_min=2026-01-31T08%3A30%3A00%2B00%3A00&query=dog';
    expect($products)->toHaveCount(110)->each->toBeInstanceOf(Product::class)
        ->and($queries)->toBe(["{$filters}&page=1&page_size=100", "{$filters}&page=2&page_size=100"]);
});
