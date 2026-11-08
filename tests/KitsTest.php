<?php

use Saloon\Http\Faking\MockClient;
use TremulantTech\Veeqo\Data\Kits\CreateKitData;
use TremulantTech\Veeqo\Data\Kits\KitContentData;
use TremulantTech\Veeqo\Requests\Kits\CreateKit;
use TremulantTech\Veeqo\Requests\Kits\GetKit;
use TremulantTech\Veeqo\Responses\Kit;
use TremulantTech\Veeqo\Responses\KitContent;
use TremulantTech\Veeqo\Responses\Sellable;

it('gets a kit as a typed Kit with its contents', function () {
    $mock = new MockClient([GetKit::class => fixtureResponse('docs/retrieve-bundle-detail')]);

    $kit = veeqo($mock)->kits()->get(220439988);

    expect($kit)->toBeInstanceOf(Kit::class)
        ->and($kit->id)->toBe(220439988)
        ->and($kit->skuCode)->toBe('KIT-123')
        ->and($kit->price)->toBe('0.03')
        ->and($kit->contents)->toHaveCount(2)->each->toBeInstanceOf(KitContent::class)
        ->and($kit->contents[0]->productVariant)->toBeInstanceOf(Sellable::class)
        ->and($kit->contents[0]->productVariant?->product->hsTariffNumber)->toBe('2501001000');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/kits/220439988'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('creates a kit, sending a flat body with its contents', function () {
    $mock = new MockClient([CreateKit::class => fixtureResponse('docs/create-a-bundle')]);

    $kit = veeqo($mock)->kits()->create(new CreateKitData(
        productVariantId: 220439988,
        contents: [new KitContentData(productVariantId: 12345, quantity: 1), new KitContentData(productVariantId: 67890)],
    ));

    expect($kit)->toBeInstanceOf(Kit::class)
        ->and($kit->id)->toBe(220439988);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/kits'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"product_variant_id":220439988,"contents":[{"product_variant_id":12345,"quantity":1},{"product_variant_id":67890}]}');
});
