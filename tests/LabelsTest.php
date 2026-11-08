<?php

use Psr\Http\Message\StreamInterface;
use Saloon\Http\Faking\MockClient;
use TremulantTech\Veeqo\Enums\LabelFormat;
use TremulantTech\Veeqo\Requests\Labels\GetLabel;

it('downloads labels as an unparsed stream, asking for the format by extension and Accept header', function () {
    $mock = new MockClient([GetLabel::class => fixtureResponse('docs/retrieve-shipping-labels')]);

    $labels = veeqo($mock)->labels()->get(shipmentIds: [3, 4], format: LabelFormat::Pdf);

    expect($labels)->toBeInstanceOf(StreamInterface::class)
        ->and((string) $labels)->toBe('{"labels_count":1}');
    $mock->assertSent(fn ($request, $response) => (string) $response->getPsrRequest()->getUri() === 'https://api.veeqo.com/shipping/labels.pdf?shipment_ids%5B%5D=3&shipment_ids%5B%5D=4'
        && $response->getPsrRequest()->getMethod() === 'GET'
        && $response->getPsrRequest()->getHeaderLine('Accept') === 'application/pdf');
});
