<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Brand
{
    public function __construct(
        public int $id,
        public int $displayPosition,
        public string $name,
        public int $productsCount,
    ) {}
}
