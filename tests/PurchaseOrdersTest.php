<?php

use Psr\Http\Message\StreamInterface;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Data\PurchaseOrderProductVariants\UpdatePurchaseOrderProductVariantData;
use TremulantTech\Veeqo\Data\PurchaseOrders\CreatePurchaseOrderData;
use TremulantTech\Veeqo\Data\PurchaseOrders\PurchaseOrderProductVariantData;
use TremulantTech\Veeqo\Data\PurchaseOrders\ReminderPurchaseOrderData;
use TremulantTech\Veeqo\Data\PurchaseOrders\UpdatePurchaseOrderData;
use TremulantTech\Veeqo\Enums\PurchaseOrderOrder;
use TremulantTech\Veeqo\Enums\PurchaseOrderState;
use TremulantTech\Veeqo\Requests\PurchaseOrderProductVariants\DeletePurchaseOrderProductVariant;
use TremulantTech\Veeqo\Requests\PurchaseOrderProductVariants\UpdatePurchaseOrderProductVariant;
use TremulantTech\Veeqo\Requests\PurchaseOrders\CreatePurchaseOrder;
use TremulantTech\Veeqo\Requests\PurchaseOrders\DownloadPurchaseOrderCsv;
use TremulantTech\Veeqo\Requests\PurchaseOrders\DownloadPurchaseOrderPdf;
use TremulantTech\Veeqo\Requests\PurchaseOrders\GetPurchaseOrder;
use TremulantTech\Veeqo\Requests\PurchaseOrders\ListPurchaseOrders;
use TremulantTech\Veeqo\Requests\PurchaseOrders\ReminderPurchaseOrder;
use TremulantTech\Veeqo\Requests\PurchaseOrders\UpdatePurchaseOrder;
use TremulantTech\Veeqo\Responses\PurchaseOrder;
use TremulantTech\Veeqo\Responses\PurchaseOrderProductVariant;

it('gets a purchase order as a typed PurchaseOrder with its line items', function () {
    $mock = new MockClient([GetPurchaseOrder::class => fixtureResponse('docs/get-purchase-order')]);

    $purchaseOrder = veeqo($mock)->purchaseOrders()->get(491);

    expect($purchaseOrder)->toBeInstanceOf(PurchaseOrder::class)
        ->and($purchaseOrder->id)->toBe(491)
        ->and($purchaseOrder->currencyRate)->toBe('1.0')
        ->and($purchaseOrder->expectedDate?->format(DATE_ATOM))->toBe('2026-06-04T00:00:00+00:00')
        ->and($purchaseOrder->supplier?->salesContactEmail)->toBe('buying@example.com')
        ->and($purchaseOrder->destinationWarehouse?->name)->toBe('Main warehouse')
        ->and($purchaseOrder->createdBy?->login)->toBe('seller')
        ->and($purchaseOrder->purchaseOrderProductVariants[0]->cost ?? null)->toBe('4.5')
        ->and($purchaseOrder->purchaseOrderProductVariants[0]->supplierProductVariant?->productVariant?->skuCode ?? null)->toBe('MOUSE-01');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/purchase_orders/491'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('lists purchase orders lazily across pages, sending enum filters and DATE_ATOM dates', function () {
    $queries = [];
    $mock = new MockClient([ListPurchaseOrders::class => function (PendingRequest $request) use (&$queries) {
        $queries[] = urldecode($request->createPsrRequest()->getUri()->getQuery());
        $purchaseOrders = json_decode(fixtureResponse('docs/list-all-purchase-orders')->body(), true);

        return MockResponse::make($request->query()->get('page') === 1 ? array_fill(0, 100, $purchaseOrders[0]) : $purchaseOrders);
    }]);

    $purchaseOrders = veeqo($mock)->purchaseOrders()->list(state: PurchaseOrderState::PastDue, order: PurchaseOrderOrder::Desc, sortBy: 'expected_date', createdBefore: new DateTimeImmutable('2026-01-31T09:00:00+02:00'));

    expect($queries)->toBe([]);
    $purchaseOrders = iterator_to_array($purchaseOrders, false);

    expect($purchaseOrders)->toHaveCount(101)->each->toBeInstanceOf(PurchaseOrder::class)
        ->and($queries)->toBe([
            'state=past_due&created[before]=2026-01-31T07:00:00+00:00&sort_by=expected_date&order=desc&page=1&page_size=100',
            'state=past_due&created[before]=2026-01-31T07:00:00+00:00&sort_by=expected_date&order=desc&page=2&page_size=100',
        ]);
});

it('sends every other purchase order filter', function () {
    $mock = new MockClient([ListPurchaseOrders::class => fixtureResponse('docs/list-all-purchase-orders')]);
    $at = new DateTimeImmutable('2026-01-01T00:00:00+00:00');

    iterator_to_array(veeqo($mock)->purchaseOrders()->list(query: 'acme', supplierId: 1748, destinationWarehouseId: 1784, productVariantId: 1226617, productVariantUpcCode: '123', referenceNumber: 'REF', createdAfter: $at, createdAtMin: $at, updatedAtMin: $at, sinceId: 5));

    $mock->assertSent(fn ($request) => $request->query()->all() === [
        'query' => 'acme', 'supplier_id' => 1748, 'destination_warehouse_id' => 1784, 'product_variant_id' => 1226617,
        'product_variant_upc_code' => '123', 'reference_number' => 'REF', 'created[after]' => '2026-01-01T00:00:00+00:00',
        'created_at_min' => '2026-01-01T00:00:00+00:00', 'updated_at_min' => '2026-01-01T00:00:00+00:00', 'since_id' => 5,
        'page' => 1, 'page_size' => 100,
    ]);
});

it('creates a purchase order, sending the envelope, wire keys and DATE_ATOM dates and dropping nulls', function () {
    $mock = new MockClient([CreatePurchaseOrder::class => fixtureResponse('docs/get-purchase-order')]);

    $purchaseOrder = veeqo($mock)->purchaseOrders()->create(new CreatePurchaseOrderData(
        supplierId: 1748,
        destinationWarehouseId: 1784,
        expectedDate: new DateTimeImmutable('2026-02-01T00:00:00+00:00'),
        currencyCode: 'GBP',
        shippingAndHandling: '12.5',
        shippingAddressLine1: '221 High St',
        purchaseOrderProductVariantsAttributes: [new PurchaseOrderProductVariantData(productVariantId: 1226617, quantity: 10, cost: '9.99', taxRate: 0.2)],
    ));

    expect($purchaseOrder)->toBeInstanceOf(PurchaseOrder::class)->and($purchaseOrder->id)->toBe(491);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/purchase_orders'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"purchase_order":{"supplier_id":1748,"destination_warehouse_id":1784,"expected_date":"2026-02-01T00:00:00+00:00","currency_code":"GBP","shipping_and_handling":"12.5","shipping_address_line_1":"221 High St","purchase_order_product_variants_attributes":[{"product_variant_id":1226617,"quantity":10,"cost":"9.99","tax_rate":0.2}]}}');
});

it('updates a purchase order, editing a line item by id, and returns the purchase order', function () {
    $mock = new MockClient([UpdatePurchaseOrder::class => fixtureResponse('docs/update-purchase-order')]);

    $purchaseOrder = veeqo($mock)->purchaseOrders()->update(491, new UpdatePurchaseOrderData(
        state: 'active',
        note: 'Updated note',
        purchaseOrderProductVariantsAttributes: [new PurchaseOrderProductVariantData(id: 1575, receivedQuantity: 5)],
    ));

    expect($purchaseOrder)->toBeInstanceOf(PurchaseOrder::class)->and($purchaseOrder->state)->toBe('active');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/purchase_orders/491'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"purchase_order":{"state":"active","note":"Updated note","purchase_order_product_variants_attributes":[{"id":1575,"received_quantity":5}]}}');
});

it('downloads a purchase order as a CSV or PDF stream', function (string $method, string $class, string $path, string $contentType) {
    $mock = new MockClient([$class => MockResponse::make('%document%', 200, ['Content-Type' => $contentType])]);

    $stream = veeqo($mock)->purchaseOrders()->{$method}(491);

    expect($stream)->toBeInstanceOf(StreamInterface::class)
        ->and((string) $stream)->toBe('%document%');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === "https://api.veeqo.com/purchase_orders/491{$path}"
        && $response->getPsrRequest()->getMethod() === 'GET'
        && $response->getPsrRequest()->getHeaderLine('Accept') === $contentType);
})->with([
    ['downloadCsv', DownloadPurchaseOrderCsv::class, '.csv', 'text/csv'],
    ['downloadPdf', DownloadPurchaseOrderPdf::class, '.pdf', 'application/pdf'],
]);

it('requests reminder emails unenveloped, returning nothing', function () {
    $mock = new MockClient([ReminderPurchaseOrder::class => fixtureResponse('docs/email-purchase-order-reminders')]);

    $result = veeqo($mock)->purchaseOrders()->reminder(new ReminderPurchaseOrderData(ids: [491, 492]));

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/purchase_orders/reminder'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"ids":[491,492]}');
});

it('updates a purchase order line item in its envelope, returning the line item', function () {
    // No doc example for this response; the line item of the get example stands in, as ADR 0011 does for GET/PUT /purchase_orders/{id}.
    $lineItem = json_decode(fixtureResponse('docs/get-purchase-order')->body(), true)['purchase_order_product_variants'][0];
    $mock = new MockClient([UpdatePurchaseOrderProductVariant::class => MockResponse::make($lineItem)]);

    $result = veeqo($mock)->purchaseOrderProductVariants()->update(purchaseOrderId: 491, id: 1575, data: new UpdatePurchaseOrderProductVariantData(quantity: 12, cost: '9.5'));

    expect($result)->toBeInstanceOf(PurchaseOrderProductVariant::class)
        ->and($result->id)->toBe(1575)
        ->and($result->taxRate)->toBe('20.0');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/purchase_orders/491/purchase_order_product_variants/1575'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"purchase_order_product_variant":{"quantity":12,"cost":"9.5"}}');
});

it('deletes a purchase order line item, returning nothing', function () {
    $mock = new MockClient([DeletePurchaseOrderProductVariant::class => fixtureResponse('docs/delete-purchase-order-line-item')]);

    $result = veeqo($mock)->purchaseOrderProductVariants()->delete(purchaseOrderId: 491, id: 1575);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/purchase_orders/491/purchase_order_product_variants/1575'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});
