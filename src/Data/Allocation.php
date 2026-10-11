<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Allocation
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'dispatch_date',
        'due_date',
        'matched_parcel_properties_criteria',
        'packed_completely',
        'preferred_shipment_options',
        'recommended_shipping_options',
        'remote_id',
    ];

    /**
     * @param list<AllocationLineItem> $lineItems
     */
    public function __construct(
        public int $id,
        public \DateTimeImmutable $updatedAt,
        public \DateTimeImmutable $createdAt,
        public float $totalWeight,
        public string $weightUnit,
        public int $allocatedById,
        public int $orderId,
        public array $lineItems,
        public Warehouse $warehouse,
        public AllocationPackage $allocationPackage,
        public bool $fromGbToNorthernIreland,
        public ?Shipment $shipment = null,
        public ?\DateTimeImmutable $preferredShipmentDate = null,
    ) {}
}
