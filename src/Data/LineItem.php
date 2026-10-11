<?php

namespace TremulantTech\Veeqo\Data;

final readonly class LineItem
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'additional_options',
    ];

    /**
     * @param numeric-string $pricePerUnit
     * @param numeric-string $taxlessDiscountPerUnit
     */
    public function __construct(
        public int $id,
        public string $pricePerUnit,
        public int $quantity,
        public float $taxRate,
        public string $taxlessDiscountPerUnit,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public string $remoteId,
        public string $title,
        public Sellable $sellable,
    ) {}
}
