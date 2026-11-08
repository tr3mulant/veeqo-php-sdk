<?php

use Saloon\Http\Faking\MockClient;
use TremulantTech\Veeqo\Requests\TrackingEvents\ListTrackingEvents;
use TremulantTech\Veeqo\Responses\TrackingEvent;

it('lists a shipment\'s tracking events as typed TrackingEvents in one request', function () {
    $mock = new MockClient([ListTrackingEvents::class => fixtureResponse('docs/view-tracking-events')]);

    $events = veeqo($mock)->trackingEvents()->list(shipmentId: 1087);

    expect($events)->toHaveCount(4)->each->toBeInstanceOf(TrackingEvent::class)
        ->and($events[1]->timestamp?->format(DATE_ATOM))->toBe('2024-12-27T00:37:00+00:00')
        ->and($events[1]->location)->toBe('Doraville, GA, US')
        ->and($events[3]->status)->toBe('delivered');
    $mock->assertSentCount(1);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/shipping/tracking_events/1087'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('lists no tracking events when Veeqo has none', function () {
    $mock = new MockClient([ListTrackingEvents::class => fixtureResponse('responses/shipping.tracking_events')]);

    expect(veeqo($mock)->trackingEvents()->list(shipmentId: 1087))->toBe([]);
});
