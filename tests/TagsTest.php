<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Data\Tags\CreateTagData;
use TremulantTech\Veeqo\Requests\Tags\CreateTag;
use TremulantTech\Veeqo\Requests\Tags\DeleteTag;
use TremulantTech\Veeqo\Requests\Tags\GetTag;
use TremulantTech\Veeqo\Requests\Tags\ListTags;
use TremulantTech\Veeqo\Responses\Tag;

it('gets a tag as a typed Tag', function () {
    $mock = new MockClient([GetTag::class => fixtureResponse('responses/tags.show')]);

    $tag = veeqo($mock)->tags()->get(1468);

    expect($tag)->toBeInstanceOf(Tag::class)
        ->and($tag->id)->toBe(1468)
        ->and($tag->colour)->toBe('redacted-e4806743')
        ->and($tag->companyId)->toBe(1004)
        ->and($tag->taggingsCount)->toBe(0);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/tags/1468'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('lists every tag in one unpaginated request, since GET /tags documents no paging', function () {
    $mock = new MockClient([ListTags::class => fixtureResponse('responses/tags.list')]);

    $tags = veeqo($mock)->tags()->list();

    expect($tags)->toHaveCount(5)->each->toBeInstanceOf(Tag::class);
    $mock->assertSentCount(1);
    $mock->assertSent(fn ($request, $response) => (string) $response->getPsrRequest()->getUri() === 'https://api.veeqo.com/tags');
});

it('creates a tag, sending a flat body', function () {
    $mock = new MockClient([CreateTag::class => fixtureResponse('docs/create-a-new-tag')]);

    $tag = veeqo($mock)->tags()->create(new CreateTagData(colour: '#009aed', name: 'Veeqo Tag'));

    expect($tag)->toBeInstanceOf(Tag::class)
        ->and($tag->id)->toBe(144441);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/tags'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"colour":"#009aed","name":"Veeqo Tag"}');
});

it('deletes a tag', function () {
    $mock = new MockClient([DeleteTag::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->tags()->delete(1468);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/tags/1468'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});
