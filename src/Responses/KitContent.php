<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class KitContent
{
    public function __construct(
        public int $id,
        public ?DateTimeImmutable $createdAt = null,
        public ?int $kitId = null,
        public ?Sellable $productVariant = null,
        public ?int $productVariantId = null,
        public ?int $quantity = null,
        public ?string $skuCode = null,
        public ?string $title = null,
        public ?int $totalAvailableStockLevel = null,
        public ?DateTimeImmutable $updatedAt = null,
    ) {}
}
