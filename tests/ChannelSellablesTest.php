<?php

use Saloon\Http\Faking\MockClient;
use TremulantTech\Veeqo\Data\ChannelSellables\ChannelProductData;
use TremulantTech\Veeqo\Data\ChannelSellables\CreateChannelSellableData;
use TremulantTech\Veeqo\Requests\ChannelSellables\CreateChannelSellable;
use TremulantTech\Veeqo\Responses\ChannelSellable;

it('creates a channel sellable with a new channel product, sending a JSON:API body with string ids', function () {
    $mock = new MockClient([CreateChannelSellable::class => fixtureResponse('docs/createchannelsellable')]);

    $sellable = veeqo($mock)->channelSellables()->create(new CreateChannelSellableData(
        sellableId: 111222333,
        channelId: 1234567,
        remoteId: 'B0EXAMPLE123',
        remoteSku: 'BLUE-WATER-BOTTLE-500ML',
        channelProductAttributes: new ChannelProductData(remoteTitle: 'Blue Water Bottle 500ml'),
    ));

    expect($sellable)->toBeInstanceOf(ChannelSellable::class)
        ->and($sellable->id)->toBe(123456789)
        ->and($sellable->remotePrice)->toBe('0.0');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/api/v2/channel_sellables'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && $response->getPsrRequest()->getHeaderLine('Accept') === 'application/vnd.api+json'
        && $response->getPsrRequest()->getHeaderLine('Content-Type') === 'application/vnd.api+json'
        && (string) $response->getPsrRequest()->getBody() === '{"data":{"type":"channel_sellables","attributes":{"sellable_id":"111222333","channel_id":"1234567","remote_id":"B0EXAMPLE123","remote_sku":"BLUE-WATER-BOTTLE-500ML","channel_product_attributes":{"remote_title":"Blue Water Bottle 500ml"}}}}');
});

it('links a channel sellable to an existing channel product through relationships', function () {
    $mock = new MockClient([CreateChannelSellable::class => fixtureResponse('docs/createchannelsellable')]);

    veeqo($mock)->channelSellables()->create(new CreateChannelSellableData(
        sellableId: 111222333,
        channelId: 1234567,
        remoteId: 'B0EXAMPLE123',
        channelProductId: 987654321,
    ));

    $mock->assertSent(fn ($request, $response) => (string) $response->getPsrRequest()->getBody()
        === '{"data":{"type":"channel_sellables","attributes":{"sellable_id":"111222333","channel_id":"1234567","remote_id":"B0EXAMPLE123"},"relationships":{"channel_product":{"data":{"type":"channel_product","id":"987654321"}}}}}');
});
