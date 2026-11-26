<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Kit
{
    /**
     * @param  numeric-string|null  $costPrice
     * @param  list<KitContent>|null  $contents
     * @param  numeric-string|null  $onHandValue
     * @param  numeric-string|null  $price
     * @param  numeric-string|null  $profit
     * @param  list<StockEntry>|null  $stockEntries
     */
    public function __construct(
        public int $id,
        public ?int $allocatedStockLevelAtAllWarehouses = null,
        public ?int $availableStockLevelAtAllWarehouses = null,
        public ?array $contents = null,
        public ?string $costPrice = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?int $createdById = null,
        public ?string $customsDescription = null,
        public ?int $estimatedDelivery = null,
        public ?string $fullTitle = null,
        public ?bool $hazmat = null,
        public ?string $hsTariffNumber = null,
        public ?SellableInventory $inventory = null,
        public ?float $margin = null,
        public ?MeasurementAttributes $measurementAttributes = null,
        public ?int $minReorderLevel = null,
        public ?string $modelNumber = null,
        public ?string $onHandValue = null,
        public ?string $originCountry = null,
        public ?string $price = null,
        public ?ProductSummary $product = null,
        public ?string $productTitle = null,
        public ?string $profit = null,
        public ?int $quantityToReorder = null,
        public ?bool $requiresReview = null,
        public ?string $sellableTitle = null,
        public ?string $skuCode = null,
        public ?array $stockEntries = null,
        public ?int $stockLevelAtAllWarehouses = null,
        public ?int $supplementaryUnits = null,
        public ?float $taxRate = null,
        public ?string $title = null,
        public ?int $totalQuantitySold = null,
        public ?string $type = null,
        public ?string $upcCode = null,
        public ?DateTimeImmutable $updatedAt = null,
        public ?float $weight = null,
        public ?float $weightGrams = null,
        public ?string $weightUnit = null,
    ) {}
}
