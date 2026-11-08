<?php

namespace TremulantTech\Veeqo\Responses;

readonly class OutboundLabelCharges
{
    /**
     * @param  numeric-string|null  $value
     */
    public function __construct(
        public ?string $unit = null,
        public ?string $value = null,
    ) {}
}
