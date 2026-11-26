<?php

namespace TremulantTech\Veeqo\Data\KitContents;

readonly class CreateKitContentData
{
    public function __construct(
        public ?int $productVariantId = null,
        public ?int $quantity = null,
    ) {}
}
