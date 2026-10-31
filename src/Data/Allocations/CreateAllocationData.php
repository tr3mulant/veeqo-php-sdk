<?php

namespace TremulantTech\Veeqo\Data\Allocations;

readonly class CreateAllocationData
{
    /**
     * @param  list<AllocationLineItemData>  $lineItemsAttributes
     */
    public function __construct(
        public int $warehouseId,
        public array $lineItemsAttributes,
        public ?bool $packedCompletely = null,
    ) {}
}
