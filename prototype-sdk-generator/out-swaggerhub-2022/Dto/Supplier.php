<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Supplier extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		public ?string $name = null,
		#[MapName('address_line_1')]
		public ?string $addressLine1 = null,
		#[MapName('address_line_2')]
		public ?string $addressLine2 = null,
		public ?string $city = null,
		public ?string $region = null,
		public ?string $country = null,
		#[MapName('post_code')]
		public ?string $postCode = null,
		#[MapName('sales_contact_name')]
		public ?string $salesContactName = null,
		#[MapName('sales_contact_email')]
		public ?string $salesContactEmail = null,
		#[MapName('sales_phone_number')]
		public ?string $salesPhoneNumber = null,
		#[MapName('accounting_contact_name')]
		public ?string $accountingContactName = null,
		#[MapName('accounting_contact_email')]
		public ?string $accountingContactEmail = null,
		#[MapName('accounting_phone_number')]
		public ?string $accountingPhoneNumber = null,
		#[MapName('currency_code')]
		public ?string $currencyCode = null,
		#[MapName('created_by_id')]
		public int|float|null $createdById = null,
		#[MapName('updated_by_id')]
		public int|float|null $updatedById = null,
		#[MapName('deleted_at')]
		public ?string $deletedAt = null,
		#[MapName('deleted_by_id')]
		public int|float|null $deletedById = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('bank_name')]
		public ?string $bankName = null,
		#[MapName('bank_account_number')]
		public ?string $bankAccountNumber = null,
		#[MapName('bank_sort_code')]
		public ?string $bankSortCode = null,
		#[MapName('credit_limit')]
		public int|float|null $creditLimit = null,
		#[MapName('active_purchase_order_count')]
		public int|float|null $activePurchaseOrderCount = null,
		#[MapName('completed_purchase_order_count')]
		public int|float|null $completedPurchaseOrderCount = null,
		#[MapName('purchase_order_template')]
		public ?string $purchaseOrderTemplate = null,
		#[MapName('reminder_email_template')]
		public ?string $reminderEmailTemplate = null,
	) {
	}
}
