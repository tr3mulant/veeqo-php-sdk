<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Data\Channels\CreateChannelData;
use TremulantTech\Veeqo\Data\Channels\UpdateChannelData;
use TremulantTech\Veeqo\Enums\ChannelTypeCode;
use TremulantTech\Veeqo\Requests\Channels\CreateChannel;
use TremulantTech\Veeqo\Requests\Channels\DeleteChannel;
use TremulantTech\Veeqo\Requests\Channels\GetChannel;
use TremulantTech\Veeqo\Requests\Channels\ListChannels;
use TremulantTech\Veeqo\Requests\Channels\UpdateChannel;
use TremulantTech\Veeqo\Responses\Channel;

it('lists channels page by page', function () {
    $mock = new MockClient([ListChannels::class => fixtureResponse('responses/channels.list')]);

    $channels = iterator_to_array(veeqo($mock)->channels()->list(), false);

    expect($channels)->toHaveCount(4)->each->toBeInstanceOf(Channel::class);
    $mock->assertSent(fn ($request, $response) => (string) $response->getPsrRequest()->getUri() === 'https://api.veeqo.com/channels?page=1&page_size=100');
});

it('gets a channel', function () {
    $mock = new MockClient([GetChannel::class => fixtureResponse('responses/channels.show')]);

    $channel = veeqo($mock)->channels()->get(1000);

    expect($channel)->toBeInstanceOf(Channel::class)
        ->and($channel->id)->toBe(1000);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/channels/1000'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('creates a channel unenveloped, sending the type code value and dropping nulls', function () {
    $mock = new MockClient([CreateChannel::class => fixtureResponse('docs/create-a-store')]);

    $channel = veeqo($mock)->channels()->create(new CreateChannelData(
        name: 'USD Direct Test',
        typeCode: ChannelTypeCode::Direct,
        currencyCode: 'USD',
        defaultWarehouseId: 22668,
        veeqoDictatesStockLevel: false,
    ));

    expect($channel)->toBeInstanceOf(Channel::class)
        ->and($channel->id)->toBe(49758);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/channels'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"name":"USD Direct Test","type_code":"direct","currency_code":"USD","default_warehouse_id":22668,"veeqo_dictates_stock_level":false}');
});

it('updates a channel unenveloped, returning nothing though the 204 carries a body', function () {
    $mock = new MockClient([UpdateChannel::class => fixtureResponse('docs/update-store-detail')]);

    $result = veeqo($mock)->channels()->update(49758, new UpdateChannelData(name: 'Phone'));

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/channels/49758'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"name":"Phone"}');
});

it('deletes a channel', function () {
    $mock = new MockClient([DeleteChannel::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->channels()->delete(49758);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/channels/49758'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});
