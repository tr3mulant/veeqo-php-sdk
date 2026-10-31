<?php

namespace TremulantTech\Veeqo\Data\Allocations;

use DateTimeInterface;

readonly class UpdateAllocationData
{
    /**
     * @param  list<AllocationLineItemData>|null  $lineItemsAttributes
     */
    public function __construct(
        public ?bool $packedCompletely = null,
        public ?float $totalWeight = null,
        public ?DateTimeInterface $dueDate = null,
        public ?DateTimeInterface $dispatchDate = null,
        public ?array $lineItemsAttributes = null,
    ) {}
}
