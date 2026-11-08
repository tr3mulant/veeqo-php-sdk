<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Data\Shipments\CreateShipmentData;
use TremulantTech\Veeqo\Data\Shipments\TrackingNumberData;
use TremulantTech\Veeqo\Requests\Shipments\CreateShipment;
use TremulantTech\Veeqo\Requests\Shipments\DeleteShipment;
use TremulantTech\Veeqo\Responses\Shipment;

it('creates a shipment, enveloping it beside its allocation and order ids', function () {
    $mock = new MockClient([CreateShipment::class => fixtureResponse('docs/create-a-shipment')]);

    $shipment = veeqo($mock)->shipments()->create(new CreateShipmentData(
        carrierId: 3,
        notifyCustomer: false,
        allocationId: 1,
        orderId: 1,
        trackingNumberAttributes: new TrackingNumberData(trackingNumber: '12345679ABC'),
    ));

    expect($shipment)->toBeInstanceOf(Shipment::class)
        ->and($shipment->id)->toBe(3)
        ->and($shipment->trackingNumber?->trackingNumber)->toBe(' 12345679ABC')
        ->and($shipment->outboundLabelCharges?->value)->toBe('5.52');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/shipments'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"shipment":{"carrier_id":3,"notify_customer":false,"tracking_number_attributes":{"tracking_number":"12345679ABC"}},"allocation_id":1,"order_id":1}');
});

it('deletes a shipment, returning nothing', function () {
    $mock = new MockClient([DeleteShipment::class => MockResponse::make(status: 204)]);

    veeqo($mock)->shipments()->delete(3);

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/shipments/3'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});
