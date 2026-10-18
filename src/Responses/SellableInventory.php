<?php

namespace TremulantTech\Veeqo\Responses;

readonly class SellableInventory
{
    public function __construct(
        public int $allocatedStockLevelAtAllWarehouses,
        public int $availableStockLevelAtAllWarehouses,
        public int $incomingStockLevelAtAllWarehouses,
        public bool $infinite,
        public int $physicalStockLevelAtAllWarehouses,
        public ?int $transitIncomingStockLevelAtAllWarehouses = null,
        public ?int $transitOutgoingStockLevelAtAllWarehouses = null,
    ) {}
}
