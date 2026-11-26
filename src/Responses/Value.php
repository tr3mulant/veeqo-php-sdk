<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Value
{
    /**
     * @param  numeric-string|null  $price
     */
    public function __construct(
        public ?string $value = null,
        public ?string $label = null,
        public ?string $price = null,
        public ?string $currency = null,
    ) {}
}
