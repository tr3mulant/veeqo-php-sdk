<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Shipment extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		#[MapName('allocation_id')]
		public int|float|null $allocationId = null,
		#[MapName('carrier_id')]
		public int|float|null $carrierId = null,
		#[MapName('shipped_by_id')]
		public int|float|null $shippedById = null,
		#[MapName('parcel_format')]
		public ?string $parcelFormat = null,
		#[MapName('postal_class')]
		public ?string $postalClass = null,
		public int|float|null $weight = null,
		#[MapName('collection_manifest_id')]
		public int|float|null $collectionManifestId = null,
		#[MapName('carrier_service_id')]
		public int|float|null $carrierServiceId = null,
		#[MapName('service_type')]
		public ?string $serviceType = null,
		#[MapName('packaging_type')]
		public ?string $packagingType = null,
		#[MapName('drop_off_type')]
		public ?string $dropOffType = null,
		#[MapName('label_url')]
		public ?string $labelUrl = null,
		#[MapName('commercial_invoice_url')]
		public ?string $commercialInvoiceUrl = null,
		#[MapName('insured_value')]
		public int|float|null $insuredValue = null,
		#[MapName('notify_customer')]
		public ?bool $notifyCustomer = null,
		#[MapName('update_remote_order')]
		public ?bool $updateRemoteOrder = null,
		#[MapName('delivery_confirmation_number')]
		public ?string $deliveryConfirmationNumber = null,
		#[MapName('tracking_url')]
		public ?string $trackingUrl = null,
		#[MapName('aftership_url')]
		public ?string $aftershipUrl = null,
		#[MapName('tracking_number')]
		public ?object $trackingNumber = null,
		#[MapName('order_id')]
		public int|float|null $orderId = null,
		public ?object $carrier = null,
		#[MapName('carrier_service')]
		public ?string $carrierService = null,
		#[MapName('carrier_country')]
		public ?string $carrierCountry = null,
		#[MapName('carrier_fees')]
		public ?array $carrierFees = null,
		#[MapName('carrier_fee')]
		public int|float|null $carrierFee = null,
		#[MapName('shipped_by')]
		public ?object $shippedBy = null,
	) {
	}
}
