<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Unavailable
{
    /**
     * @param  list<UnavailableReason>|null  $unavailableReasons
     */
    public function __construct(
        public ?string $remoteShipmentId = null,
        public ?string $title = null,
        public ?string $subCarrierId = null,
        public ?string $serviceCarrier = null,
        public ?array $unavailableReasons = null,
    ) {}
}
