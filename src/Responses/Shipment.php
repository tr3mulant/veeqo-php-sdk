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
        public int $allocationId,
        public Carrier $carrier,
        public int $carrierId,
        public DateTimeImmutable $createdAt,
        public bool $notifyCustomer,
        public int $orderId,
        public OutboundLabelCharges $outboundLabelCharges,
        public int $shippedById,
        public float $weight,
        public ?bool $hasReturnLabel = null,
        public ?string $insuredValue = null,
        public ?string $labelUrl = null,
        public ?User $shippedBy = null,
        public ?string $trackingUrl = null,
        public ?bool $updateRemoteOrder = null,
        public ?DateTimeImmutable $updatedAt = null,
    ) {}
}
