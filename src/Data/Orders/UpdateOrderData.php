<?php

namespace TremulantTech\Veeqo\Data\Orders;

use DateTimeInterface;

final readonly class UpdateOrderData
{
    /**
     * `line_items_attributes` and `employee_notes_attributes` are untyped in the spec, so not offered.
     *
     * @param  numeric-string|null  $totalDiscounts
     */
    public function __construct(
        public ?int $channelId = null,
        public ?int $deliverToId = null,
        public ?int $deliveryMethodId = null,
        public ?DateTimeInterface $dueDate = null,
        public ?string $totalDiscounts = null,
        public ?ShippingAddressData $deliverToAttributes = null,
        public ?PaymentData $paymentAttributes = null,
        public ?CustomerNoteData $customerNoteAttributes = null,
    ) {}
}
