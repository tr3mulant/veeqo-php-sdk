<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Allocation
{
    /**
     * @param  list<AllocationLineItem>  $lineItems
     */
    public function __construct(
        public int $id,
        public DateTimeImmutable $createdAt,
        public array $lineItems,
        public int $orderId,
        public float $totalWeight,
        public DateTimeImmutable $updatedAt,
        public Warehouse $warehouse,
        public ?User $allocatedBy = null,
        public ?int $allocatedById = null,
        public ?AllocationPackage $allocationPackage = null,
        public ?bool $fromGbToNorthernIreland = null,
        public ?DateTimeImmutable $preferredShipmentDate = null,
        public ?Shipment $shipment = null,
        public ?string $weightUnit = null,
    ) {}
}
