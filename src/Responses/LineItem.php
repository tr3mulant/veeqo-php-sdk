<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class LineItem
{
    /**
     * @param  numeric-string  $pricePerUnit
     * @param  numeric-string|null  $taxlessDiscountPerUnit
     */
    public function __construct(
        public int $id,
        public DateTimeImmutable $createdAt,
        public string $pricePerUnit,
        public int $quantity,
        public Sellable $sellable,
        public float $taxRate,
        public DateTimeImmutable $updatedAt,
        public ?string $remoteId = null,
        public ?string $taxlessDiscountPerUnit = null,
        public ?string $title = null,
    ) {}
}
