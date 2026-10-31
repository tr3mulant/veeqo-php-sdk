<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Data\AllocationPackages\UpdateAllocationPackageData;
use TremulantTech\Veeqo\Data\Allocations\AllocationLineItemData;
use TremulantTech\Veeqo\Data\Allocations\CreateAllocationData;
use TremulantTech\Veeqo\Data\Allocations\UpdateAllocationData;
use TremulantTech\Veeqo\Enums\DimensionsUnit;
use TremulantTech\Veeqo\Enums\PackageProvider;
use TremulantTech\Veeqo\Requests\AllocationPackages\UpdateAllocationPackage;
use TremulantTech\Veeqo\Requests\Allocations\CreateAllocation;
use TremulantTech\Veeqo\Requests\Allocations\DeleteAllocation;
use TremulantTech\Veeqo\Requests\Allocations\UpdateAllocation;
use TremulantTech\Veeqo\Responses\Allocation;
use TremulantTech\Veeqo\Responses\AllocationPackage;
use TremulantTech\Veeqo\Responses\Order;

it('allocates an order, sending the envelope and its line items', function () {
    $mock = new MockClient([CreateAllocation::class => fixtureResponse('docs/create-a-new-allocation')]);

    $order = veeqo($mock)->allocations()->create(323498, new CreateAllocationData(
        warehouseId: 5,
        lineItemsAttributes: [new AllocationLineItemData(sellableId: 1226615, quantity: 1)],
    ));

    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->allocations[0]->id)->toBe(323498);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/323498/allocations'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"allocation":{"warehouse_id":5,"line_items_attributes":[{"sellable_id":1226615,"quantity":1}]}}');
});

it('updates an allocation unenveloped, sending DATE_ATOM dates and dropping nulls', function () {
    $mock = new MockClient([UpdateAllocation::class => fixtureResponse('docs/update-allocation-detail')]);

    $allocation = veeqo($mock)->allocations()->update(11665111, 8741741, new UpdateAllocationData(
        totalWeight: 1.5,
        dispatchDate: new DateTimeImmutable('2026-01-31 09:30', new DateTimeZone('America/New_York')),
        lineItemsAttributes: [new AllocationLineItemData(sellableId: 1226615, quantity: 2)],
    ));

    expect($allocation)->toBeInstanceOf(Allocation::class)
        ->and($allocation->id)->toBe(8741741);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/11665111/allocations/8741741'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"total_weight":1.5,"dispatch_date":"2026-01-31T09:30:00-05:00","line_items_attributes":[{"sellable_id":1226615,"quantity":2}]}');
});

it('deletes an allocation', function () {
    $mock = new MockClient([DeleteAllocation::class => MockResponse::make(status: 200)]);

    $result = veeqo($mock)->allocations()->delete(11665111, 8741741);

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/11665111/allocations/8741741'
        && $response->getPsrRequest()->getMethod() === 'DELETE');
});

it('updates an allocation package, lifting the similar-shipments flag out of the envelope and unwrapping the JSON:API reply', function () {
    $mock = new MockClient([UpdateAllocationPackage::class => fixtureResponse('docs/update-allocation-package')]);

    $package = veeqo($mock)->allocationPackages()->update(447345401, new UpdateAllocationPackageData(
        width: 20.0,
        height: 10.0,
        depth: 30.0,
        packageProvider: PackageProvider::Custom,
        packageId: '4821',
        saveForSimilarShipments: false,
    ));
    veeqo($mock)->allocationPackages()->update(447345401, new UpdateAllocationPackageData(weight: 29.98));

    expect($package)->toBeInstanceOf(AllocationPackage::class)
        ->and($package->id)->toBe(221127044)
        ->and($package->allocationId)->toBe(447345401)
        ->and($package->dimensionsUnit)->toBe(DimensionsUnit::Inches)
        ->and($package->packageProvider)->toBeNull();
    expect(array_map(
        fn ($response) => [$response->getPsrRequest()->getMethod(), $response->getPendingRequest()->getUrl(), (string) $response->getPsrRequest()->getBody()],
        $mock->getRecordedResponses(),
    ))->toBe([
        ['PUT', 'https://api.veeqo.com/allocations/447345401/allocation_package', '{"allocation_package":{"width":20,"height":10,"depth":30,"package_provider":"CUSTOM","package_id":"4821"},"save_for_similar_shipments":false}'],
        ['PUT', 'https://api.veeqo.com/allocations/447345401/allocation_package', '{"allocation_package":{"weight":29.98}}'],
    ]);
});
