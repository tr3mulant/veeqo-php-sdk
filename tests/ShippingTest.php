<?php

use Saloon\Http\Faking\MockClient;
use TremulantTech\Veeqo\Data\Shipping\PurchaseLabelsData;
use TremulantTech\Veeqo\Requests\Shipping\PurchaseLabels;
use TremulantTech\Veeqo\Responses\Shipment;

it('purchases a label for a quote, enveloping the shipment beside its carrier and dropping nulls', function () {
    $mock = new MockClient([PurchaseLabels::class => fixtureResponse('docs/purchase-shipping-labels')]);

    $shipment = veeqo($mock)->shipping()->purchaseLabels(new PurchaseLabelsData(
        carrier: 'amazon_shipping_v2',
        allocationId: 55443322,
        remoteShipmentId: 'prb123abcde',
        serviceType: 'amazon_shipping_v2-2295c3aa-af8c-48a0-9f19-cd84d53cbd36',
        subCarrierId: 'UPS',
        totalNetCharge: '11.80',
        baseRate: '11.80',
        notifyCustomer: true,
        serviceCarrier: 'ups',
    ));

    expect($shipment)->toBeInstanceOf(Shipment::class)
        ->and($shipment->id)->toBe(123456789)
        ->and($shipment->labelUrl)->toBe('<LABEL_URL_PART_FROM_PURCHASE>')
        ->and($shipment->outboundLabelCharges?->unit)->toBe('USD');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/shipping/shipments'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"carrier":"amazon_shipping_v2","shipment":{"allocation_id":55443322,"remote_shipment_id":"prb123abcde","service_type":"amazon_shipping_v2-2295c3aa-af8c-48a0-9f19-cd84d53cbd36","sub_carrier_id":"UPS","total_net_charge":"11.80","base_rate":"11.80","notify_customer":true,"service_carrier":"ups"}}');
});
