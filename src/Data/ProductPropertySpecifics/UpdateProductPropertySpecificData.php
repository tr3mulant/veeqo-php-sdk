<?php

namespace TremulantTech\Veeqo\Data\ProductPropertySpecifics;

readonly class UpdateProductPropertySpecificData
{
    public function __construct(
        public ?string $productPropertyName = null,
        public ?string $value = null,
    ) {}
}
