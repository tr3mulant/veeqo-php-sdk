<?php

namespace TremulantTech\Veeqo\Data;

final readonly class StockEntry
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'location',
    ];

    /**
     * @param numeric-string $sellableOnHandValue
     */
    public function __construct(
        public int $sellableId,
        public int $warehouseId,
        public bool $infinite,
        public int $allocatedStockLevel,
        public bool $stockRunningLow,
        public \DateTimeImmutable $updatedAt,
        public int $incomingStockLevel,
        public int $transitOutgoingStockLevel,
        public Warehouse $warehouse,
        public int $physicalStockLevel,
        public int $availableStockLevel,
        public string $sellableOnHandValue,
        public int $transitIncomingStockLevel,
    ) {}
}
