<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Charge
{
    /**
     * @param  string|null  $price  Formatted with its currency symbol (`"$11.80"`), so not a numeric-string.
     */
    public function __construct(
        public ?string $price = null,
        public ?string $chargeId = null,
        public ?string $chargeTitle = null,
        public ?string $chargeType = null,
    ) {}
}
