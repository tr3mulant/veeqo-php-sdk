<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Validation
{
    public function __construct(
        public ?float $min = null,
        public ?float $max = null,
    ) {}
}
