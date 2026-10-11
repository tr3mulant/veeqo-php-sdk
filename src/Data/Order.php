<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Order
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'additional_order_level_taxless_discount_percentage',
        'business_customer_billing_address_id',
        'business_customer_shipping_address_id',
        'buyer_user_id',
        'can_pay_by_card',
        'cancel_reason',
        'cancelled_at',
        'cancelled_by',
        'contact_id',
        'customer_note',
        'employee_notes',
        'fulfillment_channel_order',
        'invoice_file_url',
        'invoice_sent_ago',
        'invoice_viewed_at',
        'merged_to_id',
        'notes',
        'payment_due_date',
        'payment_terms',
        'price_list_id',
        'returns',
        'send_refund_email',
        'tags',
        'updated_by',
    ];

    /**
     * @param numeric-string $deliveryCost
     * @param numeric-string $subtotalPrice
     * @param numeric-string $totalDiscounts
     * @param numeric-string $totalTax
     * @param numeric-string $totalFees
     * @param numeric-string $additionalOrderLevelTaxlessDiscount
     * @param numeric-string $shippingDiscount
     * @param numeric-string $adjustmentAmount
     * @param numeric-string $refundAmount
     * @param numeric-string $totalPrice
     * @param list<Allocation> $allocations
     * @param list<LineItem> $lineItems
     */
    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public string $deliveryCost,
        public \DateTimeImmutable $dispatchDate,
        public string $number,
        public bool $receiptPrinted,
        public bool $sendNotificationEmail,
        public string $status,
        public string $subtotalPrice,
        public string $totalDiscounts,
        public string $totalTax,
        public string $totalFees,
        public \DateTimeImmutable $updatedAt,
        public bool $fulfilledByAmazon,
        public bool $isAmazonPrime,
        public bool $isAmazonPremiumOrder,
        public string $additionalOrderLevelTaxlessDiscount,
        public string $shippingDiscount,
        public bool $restockShippedItems,
        public string $adjustmentAmount,
        public string $currencyCode,
        public string $pickedStatus,
        public \DateTimeImmutable $invoiceDate,
        public bool $mergedOrder,
        public string $salesRecordNumber,
        public string $refundAmount,
        public string $totalPrice,
        public User $createdBy,
        public DeliveryMethod $deliveryMethod,
        public Channel $channel,
        public array $allocations,
        public bool $allocatedCompletely,
        public bool $pickedCompletely,
        public bool $withDuties,
        public bool $canBeShipped,
        public array $lineItems,
        public ?\DateTimeImmutable $dueDate = null,
        public ?bool $international = null,
        public ?\DateTimeImmutable $shippedAt = null,
        public ?string $mergableChecksum = null,
        public ?Payment $payment = null,
        public ?ShippingAddress $deliverTo = null,
        public ?BillingAddress $billingAddress = null,
        public ?Customer $customer = null,
        public ?string $mergeableId = null,
    ) {}
}
