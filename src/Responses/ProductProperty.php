<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ProductProperty
{
    public function __construct(
        public int $id,
        public ?string $name = null,
    ) {}
}
