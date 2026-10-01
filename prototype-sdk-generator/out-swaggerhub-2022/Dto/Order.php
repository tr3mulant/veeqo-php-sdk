<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Order extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		#[MapName('cancel_reason')]
		public ?string $cancelReason = null,
		#[MapName('send_refund_email')]
		public ?bool $sendRefundEmail = null,
		#[MapName('cancelled_at')]
		public ?string $cancelledAt = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		#[MapName('delivery_cost')]
		public int|float|null $deliveryCost = null,
		#[MapName('due_date')]
		public ?string $dueDate = null,
		#[MapName('dispatch_date')]
		public ?string $dispatchDate = null,
		public ?bool $international = null,
		public ?string $notes = null,
		public ?string $number = null,
		#[MapName('receipt_printed')]
		public ?bool $receiptPrinted = null,
		#[MapName('send_notification_email')]
		public ?bool $sendNotificationEmail = null,
		#[MapName('can_pay_by_card')]
		public ?bool $canPayByCard = null,
		#[MapName('shipped_at')]
		public ?string $shippedAt = null,
		public ?string $status = null,
		#[MapName('subtotal_price')]
		public int|float|null $subtotalPrice = null,
		#[MapName('total_discounts')]
		public int|float|null $totalDiscounts = null,
		#[MapName('total_tax')]
		public int|float|null $totalTax = null,
		#[MapName('total_fees')]
		public int|float|null $totalFees = null,
		#[MapName('buyer_user_id')]
		public int|float|null $buyerUserId = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('till_id')]
		public int|float|null $tillId = null,
		#[MapName('fulfilled_by_amazon')]
		public ?bool $fulfilledByAmazon = null,
		#[MapName('is_amazon_prime')]
		public ?bool $isAmazonPrime = null,
		#[MapName('is_amazon_premium_order')]
		public ?bool $isAmazonPremiumOrder = null,
		#[MapName('additional_order_level_taxless_discount')]
		public int|float|null $additionalOrderLevelTaxlessDiscount = null,
		#[MapName('additional_order_level_taxless_discount_percentage')]
		public int|float|null $additionalOrderLevelTaxlessDiscountPercentage = null,
		#[MapName('shipping_discount')]
		public int|float|null $shippingDiscount = null,
		#[MapName('restock_shipped_items')]
		public ?bool $restockShippedItems = null,
		#[MapName('adjustment_amount')]
		public int|float|null $adjustmentAmount = null,
		#[MapName('currency_code')]
		public ?string $currencyCode = null,
		#[MapName('contact_id')]
		public int|float|null $contactId = null,
		#[MapName('business_customer_billing_address_id')]
		public int|float|null $businessCustomerBillingAddressId = null,
		#[MapName('business_customer_shipping_address_id')]
		public int|float|null $businessCustomerShippingAddressId = null,
		#[MapName('payment_due_date')]
		public ?string $paymentDueDate = null,
		#[MapName('payment_terms')]
		public int|float|null $paymentTerms = null,
		#[MapName('price_list_id')]
		public int|float|null $priceListId = null,
		#[MapName('picked_status')]
		public ?string $pickedStatus = null,
		#[MapName('invoice_file_url')]
		public ?string $invoiceFileUrl = null,
		#[MapName('invoice_date')]
		public ?string $invoiceDate = null,
		#[MapName('employee_notes')]
		public ?array $employeeNotes = null,
		public ?array $tags = null,
		public ?object $payment = null,
		#[MapName('invoice_sent_ago')]
		public ?string $invoiceSentAgo = null,
		#[MapName('invoice_viewed_at')]
		public ?string $invoiceViewedAt = null,
		#[MapName('refund_amount')]
		public int|float|null $refundAmount = null,
		#[MapName('total_price')]
		public int|float|null $totalPrice = null,
		#[MapName('cancelled_by')]
		public ?object $cancelledBy = null,
		#[MapName('created_by')]
		public ?object $createdBy = null,
		#[MapName('updated_by')]
		public ?object $updatedBy = null,
		#[MapName('delivery_method')]
		public ?object $deliveryMethod = null,
		#[MapName('deliver_to')]
		public ?object $deliverTo = null,
		#[MapName('billing_address')]
		public ?object $billingAddress = null,
		public ?Channel $channel = null,
		public ?Customer $customer = null,
		#[MapName('customer_note')]
		public ?object $customerNote = null,
		public ?array $allocations = null,
		public ?array $returns = null,
		#[MapName('allocated_completely')]
		public ?bool $allocatedCompletely = null,
		#[MapName('picked_completely')]
		public ?bool $pickedCompletely = null,
		#[MapName('fulfillment_channel_order')]
		public ?string $fulfillmentChannelOrder = null,
		#[MapName('mergeable_id')]
		public ?string $mergeableId = null,
		#[MapName('with_duties')]
		public ?bool $withDuties = null,
		#[MapName('can_be_shipped')]
		public ?bool $canBeShipped = null,
		#[MapName('line_items')]
		public ?array $lineItems = null,
	) {
	}
}
