<?php

use Saloon\Http\Faking\MockClient;
use TremulantTech\Veeqo\Data\StockEntries\UpdateStockEntryData;
use TremulantTech\Veeqo\Requests\StockEntries\GetStockEntry;
use TremulantTech\Veeqo\Requests\StockEntries\UpdateStockEntry;
use TremulantTech\Veeqo\Responses\StockEntry;
use TremulantTech\Veeqo\Responses\WarehouseSummary;

it('gets a stock entry as a typed StockEntry', function () {
    $mock = new MockClient([GetStockEntry::class => fixtureResponse('responses/stock_entry.show')]);

    $stockEntry = veeqo($mock)->stockEntries()->get(sellableId: 1344, warehouseId: 1003);

    expect($stockEntry)->toBeInstanceOf(StockEntry::class)
        ->and($stockEntry->sellableId)->toBe(1344)
        ->and($stockEntry->sellableOnHandValue)->toBe('0')
        ->and($stockEntry->warehouse)->toBeInstanceOf(WarehouseSummary::class)
        ->and($stockEntry->location)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/sellables/1344/warehouses/1003/stock_entry'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('updates a stock entry, sending the envelope and dropping nulls', function () {
    $mock = new MockClient([UpdateStockEntry::class => fixtureResponse('docs/update-a-stock-entry')]);

    $stockEntry = veeqo($mock)->stockEntries()->update(sellableId: 14504036, warehouseId: 22668, data: new UpdateStockEntryData(physicalStockLevel: 200, infinite: false));

    expect($stockEntry)->toBeInstanceOf(StockEntry::class)
        ->and($stockEntry->physicalStockLevel)->toBe(200)
        ->and($stockEntry->location)->toBe('Hat Rack');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/sellables/14504036/warehouses/22668/stock_entry'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"stock_entry":{"physical_stock_level":200,"infinite":false}}');
});
