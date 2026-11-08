<?php

namespace TremulantTech\Veeqo\Data\Products;

readonly class ImageData
{
    public function __construct(
        public ?string $src = null,
        public ?int $displayPosition = null,
    ) {}
}
