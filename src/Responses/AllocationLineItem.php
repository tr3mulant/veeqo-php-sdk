<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class AllocationLineItem
{
    public function __construct(
        public int $id,
        public DateTimeImmutable $createdAt,
        public int $quantity,
        public Sellable $sellable,
        public DateTimeImmutable $updatedAt,
        public ?int $pickedQuantity = null,
    ) {}
}
