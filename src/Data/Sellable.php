<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Sellable
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'customs_description',
        'deleted_at',
        'estimated_delivery',
        'hazmat',
        'hs_code_extensions',
        'hs_tariff_number',
        'mid_code',
        'origin_country',
        'reorders',
        'taric_code',
        'variant_property_specifics',
    ];

    /**
     * @param numeric-string $price
     * @param numeric-string $costPrice
     * @param numeric-string $profit
     * @param list<StockEntry> $stockEntries
     * @param list<VariantOptionSpecific> $variantOptionSpecifics
     * @param list<Image> $images
     */
    public function __construct(
        public int $allocatedStockLevelAtAllWarehouses,
        public int $id,
        public string $type,
        public string $title,
        public string $skuCode,
        public string $upcCode,
        public string $modelNumber,
        public string $price,
        public string $costPrice,
        public int $minReorderLevel,
        public int $quantityToReorder,
        public int $createdById,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public float $weightGrams,
        public string $weightUnit,
        public string $productTitle,
        public string $fullTitle,
        public string $sellableTitle,
        public string $profit,
        public float $margin,
        public float $taxRate,
        public int $supplementaryUnits,
        public string $imageUrl,
        public Product $product,
        public array $stockEntries,
        public array $variantOptionSpecifics,
        public array $images,
        public MeasurementAttributes $measurementAttributes,
        public string $mainThumbnailUrl,
        public int $availableStockLevelAtAllWarehouses,
        public int $stockLevelAtAllWarehouses,
        public Inventory $inventory,
        public float $weight,
    ) {}
}
