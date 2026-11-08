<?php

namespace TremulantTech\Veeqo\Data\Products;

readonly class ProductVariantData
{
    /**
     * @param  numeric-string  $price
     */
    public function __construct(
        public string $title,
        public string $price,
        public string $skuCode,
        public ?float $weightGrams = null,
        public ?string $upcCode = null,
        public ?int $minReorderLevel = null,
        public ?int $quantityToReorder = null,
        public ?float $taxRate = null,
    ) {}
}
