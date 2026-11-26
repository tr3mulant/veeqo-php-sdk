<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ProductPropertySpecific
{
    public function __construct(
        public int $id,
        public ?int $productPropertyId = null,
        public ?string $productPropertyName = null,
        public ?string $value = null,
    ) {}
}
