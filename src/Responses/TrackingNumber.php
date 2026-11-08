<?php

namespace TremulantTech\Veeqo\Responses;

readonly class TrackingNumber
{
    public function __construct(
        public int $id,
        public ?string $trackingNumber = null,
    ) {}
}
