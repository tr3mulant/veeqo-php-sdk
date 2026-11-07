<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Data\BulkTagging\CreateBulkTaggingData;
use TremulantTech\Veeqo\Data\BulkTagging\DeleteBulkTaggingData;
use TremulantTech\Veeqo\Requests\BulkTagging\CreateBulkTagging;
use TremulantTech\Veeqo\Requests\BulkTagging\DeleteBulkTagging;

it('tags orders in bulk, sending a flat body without the unset ids', function () {
    $mock = new MockClient([CreateBulkTagging::class => MockResponse::make(status: 200)]);

    $result = veeqo($mock)->bulkTagging()->create(new CreateBulkTaggingData(tagIds: [4405, 11159], orderIds: [1, 2]));

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/bulk_tagging'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"tag_ids":[4405,11159],"order_ids":[1,2]}');
});

it('untags products in bulk, sending the ids in the DELETE body', function () {
    $mock = new MockClient([DeleteBulkTagging::class => MockResponse::make(status: 200)]);

    $result = veeqo($mock)->bulkTagging()->delete(new DeleteBulkTaggingData(tagIds: [4405], productIds: [7]));

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/bulk_tagging'
        && $response->getPsrRequest()->getMethod() === 'DELETE'
        && (string) $response->getPsrRequest()->getBody() === '{"tag_ids":[4405],"product_ids":[7]}');
});
