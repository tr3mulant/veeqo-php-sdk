<?php

namespace TremulantTech\Veeqo\Data\StockEntries;

readonly class UpdateStockEntryData
{
    public function __construct(
        public int $physicalStockLevel,
        public bool $infinite,
        public ?string $location = null,
    ) {}
}
