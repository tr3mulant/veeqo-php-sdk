<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Order
{
    /**
     * @param  list<Allocation>  $allocations
     * @param  numeric-string  $deliveryCost
     * @param  list<Note>  $employeeNotes
     * @param  list<LineItem>  $lineItems
     * @param  list<OrderReturn>  $returns
     * @param  numeric-string  $subtotalPrice
     * @param  numeric-string  $totalDiscounts
     * @param  numeric-string  $totalPrice
     * @param  numeric-string  $totalTax
     * @param  numeric-string|null  $additionalOrderLevelTaxlessDiscount
     * @param  numeric-string|null  $adjustmentAmount
     * @param  numeric-string|null  $refundAmount
     * @param  numeric-string|null  $shippingDiscount
     * @param  numeric-string|null  $totalFees
     */
    public function __construct(
        public int $id,
        public bool $allocatedCompletely,
        public array $allocations,
        public Channel $channel,
        public DateTimeImmutable $createdAt,
        public User $createdBy,
        public string $deliveryCost,
        public DeliveryMethodSummary $deliveryMethod,
        public array $employeeNotes,
        public bool $fulfilledByAmazon,
        public array $lineItems,
        public string $number,
        public bool $receiptPrinted,
        public array $returns,
        public bool $sendNotificationEmail,
        public string $status,
        public string $subtotalPrice,
        public string $totalDiscounts,
        public string $totalPrice,
        public string $totalTax,
        public DateTimeImmutable $updatedAt,
        public ?string $additionalOrderLevelTaxlessDiscount = null,
        public ?string $adjustmentAmount = null,
        public ?Address $billingAddress = null,
        public ?bool $canBeShipped = null,
        public ?string $currencyCode = null,
        public ?Customer $customer = null,
        public ?Note $customerNote = null,
        public ?Address $deliverTo = null,
        public ?DateTimeImmutable $dispatchDate = null,
        public ?DateTimeImmutable $dueDate = null,
        public ?bool $international = null,
        public ?DateTimeImmutable $invoiceDate = null,
        public ?bool $isAmazonPremiumOrder = null,
        public ?bool $isAmazonPrime = null,
        public ?string $mergableChecksum = null,
        public ?string $mergeableId = null,
        public ?bool $mergedOrder = null,
        public ?Payment $payment = null,
        public ?bool $pickedCompletely = null,
        public ?string $pickedStatus = null,
        public ?string $refundAmount = null,
        public ?bool $restockShippedItems = null,
        public ?string $salesRecordNumber = null,
        public ?DateTimeImmutable $shippedAt = null,
        public ?string $shippingDiscount = null,
        public ?string $totalFees = null,
        public ?bool $withDuties = null,
    ) {}
}
