<?php

namespace TremulantTech\Veeqo\Data\Orders;

use DateTimeInterface;

/**
 * `line_items_attributes` and `employee_notes_attributes` are left out until their shape is known; set them with body()->set() (ADR 0010).
 */
readonly class UpdateOrderData
{
    /**
     * @param  numeric-string|null  $totalDiscounts
     */
    public function __construct(
        public ?int $channelId = null,
        public ?int $deliverToId = null,
        public ?int $deliveryMethodId = null,
        public ?DateTimeInterface $dueDate = null,
        public ?string $totalDiscounts = null,
        public ?AddressData $deliverToAttributes = null,
        public ?PaymentData $paymentAttributes = null,
        public ?NoteData $customerNoteAttributes = null,
    ) {}
}
