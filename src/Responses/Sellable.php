<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Sellable
{
    /**
     * @param  numeric-string  $costPrice
     * @param  numeric-string  $price
     * @param  numeric-string  $profit
     * @param  list<StockEntry>  $stockEntries
     * @param  list<Image>  $images
     * @param  numeric-string|null  $onHandValue
     * @param  list<VariantOptionSpecific>  $variantOptionSpecifics
     */
    public function __construct(
        public int $id,
        public string $costPrice,
        public DateTimeImmutable $createdAt,
        public int $createdById,
        public string $fullTitle,
        public float $margin,
        public int $minReorderLevel,
        public string $modelNumber,
        public string $price,
        public ProductSummary $product,
        public string $productTitle,
        public string $profit,
        public int $quantityToReorder,
        public string $sellableTitle,
        public string $skuCode,
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
        public array $images = [],
        public ?SellableInventory $inventory = null,
        public ?string $mainThumbnailUrl = null,
        public ?MeasurementAttributes $measurementAttributes = null,
        public ?string $onHandValue = null,
        public ?string $originCountry = null,
        public ?int $stockLevelAtAllWarehouses = null,
        public ?int $supplementaryUnits = null,
        public ?int $totalQuantitySold = null,
        public array $variantOptionSpecifics = [],
        public ?float $weight = null,
        public ?float $weightGrams = null,
        public ?string $weightUnit = null,
    ) {}
}
