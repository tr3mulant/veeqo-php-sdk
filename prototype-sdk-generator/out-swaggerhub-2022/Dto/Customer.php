<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Customer extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		public ?string $email = null,
		public ?string $phone = null,
		public ?string $mobile = null,
		public ?string $notes = null,
		public ?string $account = null,
		#[MapName('created_by_id')]
		public int|float|null $createdById = null,
		#[MapName('customer_type')]
		public ?string $customerType = null,
		#[MapName('company_name')]
		public ?string $companyName = null,
		#[MapName('payment_terms')]
		public int|float|null $paymentTerms = null,
		public ?string $currency = null,
		#[MapName('payment_method')]
		public ?string $paymentMethod = null,
		public int|float|null $discount = null,
		#[MapName('minimum_order_value')]
		public int|float|null $minimumOrderValue = null,
		#[MapName('delivery_method_id')]
		public int|float|null $deliveryMethodId = null,
		#[MapName('full_name')]
		public ?string $fullName = null,
		#[MapName('price_list_id')]
		public int|float|null $priceListId = null,
		#[MapName('remote_id')]
		public ?string $remoteId = null,
		public ?array $contacts = null,
		public ?array $addresses = null,
		#[MapName('price_list')]
		public ?object $priceList = null,
		#[MapName('billing_address')]
		public ?object $billingAddress = null,
		#[MapName('shipping_addresses')]
		public ?array $shippingAddresses = null,
		#[MapName('contact_data')]
		public ?object $contactData = null,
		#[MapName('last_used_shipping_address')]
		public ?array $lastUsedShippingAddress = null,
		#[MapName('delivery_method')]
		public ?DeliveryMethod $deliveryMethod = null,
	) {
	}
}
