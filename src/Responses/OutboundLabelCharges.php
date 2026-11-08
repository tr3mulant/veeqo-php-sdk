<?php

namespace TremulantTech\Veeqo\Responses;

readonly class OutboundLabelCharges
{
    /**
     * @param  string|null  $value  Money, but not a numeric-string: Veeqo's purchase-labels doc example sends `"8.56,"`.
     */
    public function __construct(
        public ?string $unit = null,
        public ?string $value = null,
    ) {}
}
