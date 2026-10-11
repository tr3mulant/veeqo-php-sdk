<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Inventory
{
    public function __construct(
        public bool $infinite,
        public int $physicalStockLevelAtAllWarehouses,
        public int $allocatedStockLevelAtAllWarehouses,
        public int $availableStockLevelAtAllWarehouses,
        public int $incomingStockLevelAtAllWarehouses,
        public int $transitOutgoingStockLevelAtAllWarehouses,
        public int $transitIncomingStockLevelAtAllWarehouses,
    ) {}
}
