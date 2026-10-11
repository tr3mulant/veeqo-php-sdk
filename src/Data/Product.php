<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Product
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'deleted_at',
        'deleted_by_id',
        'estimated_delivery',
        'hs_tariff_number',
        'mid_code',
        'taric_code',
    ];

    public function __construct(
        public int $id,
        public string $title,
        public float $weight,
        public string $originCountry,
        public float $taxRate,
        public string $description,
        public string $mainImageSrc,
    ) {}
}
