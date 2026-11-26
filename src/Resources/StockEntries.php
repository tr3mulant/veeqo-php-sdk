<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\StockEntries\UpdateStockEntryData;
use TremulantTech\Veeqo\Requests\StockEntries\GetStockEntry;
use TremulantTech\Veeqo\Requests\StockEntries\UpdateStockEntry;
use TremulantTech\Veeqo\Responses\StockEntry;
use TremulantTech\Veeqo\Veeqo;

class StockEntries
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function get(int $sellableId, int $warehouseId): StockEntry
    {
        return $this->veeqo->hydrate(StockEntry::class, $this->veeqo->send(new GetStockEntry($sellableId, $warehouseId)));
    }

    public function update(int $sellableId, int $warehouseId, UpdateStockEntryData $data): StockEntry
    {
        return $this->veeqo->hydrate(StockEntry::class, $this->veeqo->send(new UpdateStockEntry($sellableId, $warehouseId, $data)));
    }
}
