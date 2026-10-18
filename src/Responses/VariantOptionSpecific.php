<?php

namespace TremulantTech\Veeqo\Responses;

readonly class VariantOptionSpecific
{
    public function __construct(
        public int $id,
        public int $productPropertyId,
        public string $productPropertyName,
        public int $productSpecificId,
        public ?string $value = null,
    ) {}
}
