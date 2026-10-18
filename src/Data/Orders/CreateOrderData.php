<?php

namespace TremulantTech\Veeqo\Data\Orders;

use DateTimeInterface;

/**
 * `employee_note_attributes` is left out until its shape is known; set it with body()->set() (ADR 0010).
 */
readonly class CreateOrderData
{
    /**
     * @param  list<LineItemData>  $lineItemsAttributes
     * @param  int|null  $customerId  Optional if `customer_attributes` (untyped; set it with body()->set()) is sent.
     * @param  int|null  $deliverToId  Optional if $deliverToAttributes is sent.
     * @param  numeric-string|null  $totalDiscounts
     */
    public function __construct(
        public int $channelId,
        public int $deliveryMethodId,
        public array $lineItemsAttributes,
        public ?int $customerId = null,
        public ?int $deliverToId = null,
        public ?DateTimeInterface $dueDate = null,
        public ?string $number = null,
        public ?bool $sendNotificationEmail = null,
        public ?string $totalDiscounts = null,
        public ?AddressData $deliverToAttributes = null,
        public ?PaymentData $paymentAttributes = null,
        public ?NoteData $customerNoteAttributes = null,
    ) {}
}
