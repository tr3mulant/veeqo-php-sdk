<?php

namespace TremulantTech\Veeqo\Data\Allocations;

readonly class AllocationLineItemData
{
    public function __construct(
        public ?int $sellableId = null,
        public ?int $quantity = null,
    ) {}
}
