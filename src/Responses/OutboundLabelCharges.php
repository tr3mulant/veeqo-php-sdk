<?php

namespace TremulantTech\Veeqo\Responses;

readonly class OutboundLabelCharges
{
    public function __construct(
        public ?string $unit = null,
        /** @var numeric-string|null */
        public ?string $value = null,
    ) {}
}
