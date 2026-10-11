<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Shipment
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'aftership_url',
        'carrier_country',
        'carrier_fee',
        'carrier_fees',
        'carrier_service',
        'carrier_service_id',
        'charges',
        'collection_manifest_id',
        'commercial_invoice_url',
        'delivery_confirmation_number',
        'drop_off_type',
        'label_url',
        'outbound_label_charges',
        'outbound_shipment_date',
        'packaging_type',
        'parcel_format',
        'postal_class',
        'service_carrier_name',
        'service_name',
        'service_type',
        'short_service_name',
        'sub_carrier_id',
        'tracking_number',
        'tracking_url',
        'update_remote_order',
    ];

    /**
     * @param numeric-string $insuredValue
     */
    public function __construct(
        public int $id,
        public \DateTimeImmutable $updatedAt,
        public \DateTimeImmutable $createdAt,
        public int $allocationId,
        public int $carrierId,
        public int $shippedById,
        public float $weight,
        public string $insuredValue,
        public bool $notifyCustomer,
        public int $orderId,
        public Carrier $carrier,
        public User $shippedBy,
        public bool $hasReturnLabel,
    ) {}
}
