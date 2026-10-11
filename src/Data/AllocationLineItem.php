<?php

namespace TremulantTech\Veeqo\Data;

final readonly class AllocationLineItem
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'warehouse_sublocation',
    ];

    public function __construct(
        public int $id,
        public int $quantity,
        public int $pickedQuantity,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public Sellable $sellable,
    ) {}
}
