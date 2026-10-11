<?php

namespace TremulantTech\Veeqo\Data;

final readonly class VariantOptionSpecific
{
    public function __construct(
        public int $id,
        public int $productSpecificId,
        public int $productPropertyId,
        public string $productPropertyName,
        public ?string $value = null,
    ) {}
}
