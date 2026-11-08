<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Shipment
{
    /**
     * @param  numeric-string|null  $insuredValue
     */
    public function __construct(
        public int $id,
        public ?int $allocationId = null,
        public ?Carrier $carrier = null,
        public ?int $carrierId = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?bool $notifyCustomer = null,
        public ?int $orderId = null,
        public ?OutboundLabelCharges $outboundLabelCharges = null,
        public ?int $shippedById = null,
        public ?float $weight = null,
        public ?bool $hasReturnLabel = null,
        public ?string $insuredValue = null,
        public ?string $labelUrl = null,
        public ?User $shippedBy = null,
        public ?TrackingNumber $trackingNumber = null,
        public ?string $trackingUrl = null,
        public ?bool $updateRemoteOrder = null,
        public ?DateTimeImmutable $updatedAt = null,
    ) {}
}
