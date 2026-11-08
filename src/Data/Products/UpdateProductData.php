<?php

namespace TremulantTech\Veeqo\Data\Products;

readonly class UpdateProductData
{
    /**
     * @param  list<ProductVariantData>|null  $productVariantsAttributes
     * @param  list<ImageData>|null  $imagesAttributes
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?int $estimatedDelivery = null,
        public ?string $notes = null,
        public ?int $productBrandId = null,
        public ?array $productVariantsAttributes = null,
        public ?array $imagesAttributes = null,
    ) {}
}
