<?php

namespace TremulantTech\Veeqo\Data\Shipments;

readonly class TrackingNumberData
{
    public function __construct(
        public string $trackingNumber,
    ) {}
}
