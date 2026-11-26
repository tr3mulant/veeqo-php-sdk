<?php

namespace TremulantTech\Veeqo\Data\Kits;

readonly class KitContentData
{
    public function __construct(
        public ?int $productVariantId = null,
        public ?int $quantity = null,
    ) {}
}
