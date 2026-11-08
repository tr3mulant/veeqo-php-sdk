<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Data\KitContents\CreateKitContentData;
use TremulantTech\Veeqo\Data\KitContents\UpdateKitContentData;
use TremulantTech\Veeqo\Requests\KitContents\CreateKitContent;
use TremulantTech\Veeqo\Requests\KitContents\DeleteKitContent;
use TremulantTech\Veeqo\Requests\KitContents\GetKitContent;
use TremulantTech\Veeqo\Requests\KitContents\UpdateKitContent;
use TremulantTech\Veeqo\Responses\KitContent;

it('gets a kit content as a typed KitContent', function () {
    $mock = new MockClient([GetKitContent::class => fixtureResponse('docs/retrieve-bundle-content')]);

    $content = veeqo($mock)->kitContents()->get(kitId: 220439988, id: 1339393);

    expect($content)->toBeInstanceOf(KitContent::class)
        ->and($content->id)->toBe(1339393)
        ->and($content->kitId)->toBe(220439988)
        ->and($content->productVariant?->price)->toBe('0')
        ->and($content->createdAt?->format(DATE_ATOM))->toBe('2024-03-29T12:14:31+00:00');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/kits/220439988/kit_contents/1339393'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('creates a kit content, sending it in its envelope', function () {
    $mock = new MockClient([CreateKitContent::class => fixtureResponse('docs/create-bundle-content')]);

    $content = veeqo($mock)->kitContents()->create(220439988, new CreateKitContentData(productVariantId: 139784571, quantity: 10));

    expect($content)->toBeInstanceOf(KitContent::class)
        ->and($content->id)->toBe(1339393);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/kits/220439988/kit_contents'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"kit_content":{"product_variant_id":139784571,"quantity":10}}');
});

it('updates a kit content, dropping nulls', function () {
    $mock = new MockClient([UpdateKitContent::class => fixtureResponse('docs/update-bundle-content')]);

    $content = veeqo($mock)->kitContents()->update(220439988, 1339393, new UpdateKitContentData(quantity: 10));

    expect($content)->toBeInstanceOf(KitContent::class)
        ->and($content->id)->toBe(1339393);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/kits/220439988/kit_contents/1339393'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"kit_content":{"quantity":10}}');
});

it('deletes a kit content', function () {
    $mock = new MockClient([DeleteKitContent::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->kitContents()->delete(220439988, 1339393);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/kits/220439988/kit_contents/1339393'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});
