<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class StockEntry
{
    public function __construct(
        public int $allocatedStockLevel,
        public int $availableStockLevel,
        public int $incomingStockLevel,
        public bool $infinite,
        public int $physicalStockLevel,
        public int $sellableId,
        /** @var numeric-string */
        public string $sellableOnHandValue,
        public DateTimeImmutable $updatedAt,
        public WarehouseSummary $warehouse,
        public int $warehouseId,
        public ?int $id = null,
        public ?string $location = null,
        public ?bool $stockRunningLow = null,
        public ?int $transitIncomingStockLevel = null,
        public ?int $transitOutgoingStockLevel = null,
    ) {}
}
