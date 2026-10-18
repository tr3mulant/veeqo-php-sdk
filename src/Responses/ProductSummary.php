<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ProductSummary
{
    public function __construct(
        public int $id,
        public string $description,
        public float $taxRate,
        public string $title,
        public float $weight,
        public ?int $estimatedDelivery = null,
        public ?Image $mainImage = null,
        public ?string $mainImageSrc = null,
        public ?string $originCountry = null,
    ) {}
}
