<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class LineItem
{
    public function __construct(
        public int $id,
        public DateTimeImmutable $createdAt,
        /** @var numeric-string */
        public string $pricePerUnit,
        public int $quantity,
        public Sellable $sellable,
        public float $taxRate,
        public DateTimeImmutable $updatedAt,
        public ?string $remoteId = null,
        /** @var numeric-string|null */
        public ?string $taxlessDiscountPerUnit = null,
        public ?string $title = null,
    ) {}
}
