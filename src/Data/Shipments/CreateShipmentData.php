<?php

namespace TremulantTech\Veeqo\Data\Shipments;

readonly class CreateShipmentData
{
    public function __construct(
        public int $carrierId,
        public bool $notifyCustomer,
        public int $allocationId,
        public int $orderId,
        public ?TrackingNumberData $trackingNumberAttributes = null,
        public ?bool $updateRemoteOrder = null,
    ) {}
}
