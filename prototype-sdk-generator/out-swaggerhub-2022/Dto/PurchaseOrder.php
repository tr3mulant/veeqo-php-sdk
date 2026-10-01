<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class PurchaseOrder extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		public ?string $number = null,
		#[MapName('reference_number')]
		public ?string $referenceNumber = null,
		#[MapName('user_id')]
		public int|float|null $userId = null,
		#[MapName('supplier_id')]
		public int|float|null $supplierId = null,
		#[MapName('destination_warehouse_id')]
		public int|float|null $destinationWarehouseId = null,
		public ?string $state = null,
		#[MapName('created_by_id')]
		public int|float|null $createdById = null,
		#[MapName('updated_by_id')]
		public int|float|null $updatedById = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('expected_date')]
		public ?string $expectedDate = null,
		#[MapName('estimated_delivery_days')]
		public int|float|null $estimatedDeliveryDays = null,
		#[MapName('currency_code')]
		public ?string $currencyCode = null,
		#[MapName('currency_rate')]
		public ?string $currencyRate = null,
		#[MapName('shipping_address_line_1')]
		public ?string $shippingAddressLine1 = null,
		#[MapName('shipping_address_line_2')]
		public ?string $shippingAddressLine2 = null,
		#[MapName('shipping_address_town')]
		public ?string $shippingAddressTown = null,
		#[MapName('shipping_address_state')]
		public ?string $shippingAddressState = null,
		#[MapName('shipping_address_postcode')]
		public ?string $shippingAddressPostcode = null,
		#[MapName('shipping_address_country')]
		public ?string $shippingAddressCountry = null,
		#[MapName('billing_address_line_1')]
		public ?string $billingAddressLine1 = null,
		#[MapName('billing_address_line_2')]
		public ?string $billingAddressLine2 = null,
		#[MapName('billing_address_town')]
		public ?string $billingAddressTown = null,
		#[MapName('billing_address_state')]
		public ?string $billingAddressState = null,
		#[MapName('billing_address_postcode')]
		public ?string $billingAddressPostcode = null,
		#[MapName('billing_address_country')]
		public ?string $billingAddressCountry = null,
		#[MapName('product_variants_count')]
		public int|float|null $productVariantsCount = null,
		#[MapName('received_product_variants_count')]
		public int|float|null $receivedProductVariantsCount = null,
		public ?string $note = null,
		#[MapName('units_ordered')]
		public int|float|null $unitsOrdered = null,
		#[MapName('units_received')]
		public int|float|null $unitsReceived = null,
		public int|float|null $subtotal = null,
		#[MapName('total_tax')]
		public int|float|null $totalTax = null,
		#[MapName('shipping_and_handling')]
		public int|float|null $shippingAndHandling = null,
		#[MapName('total_including_tax')]
		public int|float|null $totalIncludingTax = null,
		#[MapName('total_excluding_tax')]
		public int|float|null $totalExcludingTax = null,
		#[MapName('supplier_report_format')]
		public ?string $supplierReportFormat = null,
		#[MapName('sent_at')]
		public ?string $sentAt = null,
		#[MapName('received_at')]
		public ?string $receivedAt = null,
		public ?Supplier $supplier = null,
		#[MapName('destination_warehouse')]
		public ?Warehouse $destinationWarehouse = null,
		#[MapName('created_by')]
		public ?object $createdBy = null,
		#[MapName('purchase_order_product_variants')]
		public ?array $purchaseOrderProductVariants = null,
	) {
	}
}
