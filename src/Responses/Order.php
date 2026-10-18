<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Order
{
    public function __construct(
        public int $id,
        public bool $allocatedCompletely,
        /** @var list<Allocation> */
        public array $allocations,
        public Channel $channel,
        public DateTimeImmutable $createdAt,
        public User $createdBy,
        /** @var numeric-string */
        public string $deliveryCost,
        public DeliveryMethodSummary $deliveryMethod,
        /** @var list<Note> */
        public array $employeeNotes,
        public bool $fulfilledByAmazon,
        /** @var list<LineItem> */
        public array $lineItems,
        public string $number,
        public bool $receiptPrinted,
        /** @var list<OrderReturn> */
        public array $returns,
        public bool $sendNotificationEmail,
        public string $status,
        /** @var numeric-string */
        public string $subtotalPrice,
        /** @var numeric-string */
        public string $totalDiscounts,
        /** @var numeric-string */
        public string $totalPrice,
        /** @var numeric-string */
        public string $totalTax,
        public DateTimeImmutable $updatedAt,
        /** @var numeric-string|null */
        public ?string $additionalOrderLevelTaxlessDiscount = null,
        /** @var numeric-string|null */
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
        /** @var numeric-string|null */
        public ?string $refundAmount = null,
        public ?bool $restockShippedItems = null,
        public ?string $salesRecordNumber = null,
        public ?DateTimeImmutable $shippedAt = null,
        /** @var numeric-string|null */
        public ?string $shippingDiscount = null,
        /** @var numeric-string|null */
        public ?string $totalFees = null,
        public ?bool $withDuties = null,
    ) {}
}
