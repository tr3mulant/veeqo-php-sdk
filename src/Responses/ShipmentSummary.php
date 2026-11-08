<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

/**
 * The shipment returned by `shipments()->create()` and `shipping()->purchaseLabels()`, typed from their doc
 * examples only (ADR 0011), so the live-verified {@see Shipment} an allocation embeds stays strict.
 */
readonly class ShipmentSummary
{
    public function __construct(
        public int $id,
        public ?int $allocationId = null,
        public ?Carrier $carrier = null,
        public ?int $carrierId = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?string $labelUrl = null,
        public ?bool $notifyCustomer = null,
        public ?int $orderId = null,
        public ?OutboundLabelCharges $outboundLabelCharges = null,
        public ?int $shippedById = null,
        public ?TrackingNumber $trackingNumber = null,
        public ?string $trackingUrl = null,
        public ?bool $updateRemoteOrder = null,
        public ?float $weight = null,
    ) {}
}
