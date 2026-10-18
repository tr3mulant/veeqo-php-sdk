<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Sellable
{
    public function __construct(
        public int $id,
        /** @var numeric-string */
        public string $costPrice,
        public DateTimeImmutable $createdAt,
        public int $createdById,
        public string $fullTitle,
        public float $margin,
        public int $minReorderLevel,
        public string $modelNumber,
        /** @var numeric-string */
        public string $price,
        public ProductSummary $product,
        public string $productTitle,
        /** @var numeric-string */
        public string $profit,
        public int $quantityToReorder,
        public string $sellableTitle,
        public string $skuCode,
        /** @var list<StockEntry> */
        public array $stockEntries,
        public float $taxRate,
        public string $title,
        public string $type,
        public string $upcCode,
        public DateTimeImmutable $updatedAt,
        public ?int $allocatedStockLevelAtAllWarehouses = null,
        public ?int $availableStockLevelAtAllWarehouses = null,
        public ?string $customsDescription = null,
        public ?int $estimatedDelivery = null,
        public ?bool $hazmat = null,
        public ?string $hsTariffNumber = null,
        public ?string $imageUrl = null,
        /** @var list<Image> */
        public array $images = [],
        public ?SellableInventory $inventory = null,
        public ?string $mainThumbnailUrl = null,
        public ?MeasurementAttributes $measurementAttributes = null,
        /** @var numeric-string|null */
        public ?string $onHandValue = null,
        public ?string $originCountry = null,
        public ?int $stockLevelAtAllWarehouses = null,
        public ?int $supplementaryUnits = null,
        public ?int $totalQuantitySold = null,
        /** @var list<VariantOptionSpecific> */
        public array $variantOptionSpecifics = [],
        public ?float $weight = null,
        public ?float $weightGrams = null,
        public ?string $weightUnit = null,
    ) {}
}
