<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Size
{
    public function __construct(
        public ?float $length = null,
        public ?string $unit = null,
        public ?float $width = null,
    ) {}
}
