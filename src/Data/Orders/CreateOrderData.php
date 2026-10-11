<?php

namespace TremulantTech\Veeqo\Data\Orders;

use DateTimeInterface;

final readonly class CreateOrderData
{
    /**
     * `customer_id` and `deliver_to_id` are spec-required but documented as optional
     * when their `*_attributes` counterpart is sent, so neither is enforced (ADR 0010).
     *
     * @param  list<LineItemData>  $lineItemsAttributes
     * @param  numeric-string|null  $totalDiscounts
     */
    public function __construct(
        public int $channelId,
        public int $deliveryMethodId,
        public array $lineItemsAttributes,
        public ?int $customerId = null,
        public ?int $deliverToId = null,
        public ?ShippingAddressData $deliverToAttributes = null,
        public ?DateTimeInterface $dueDate = null,
        public ?string $number = null,
        public ?bool $sendNotificationEmail = null,
        public ?string $totalDiscounts = null,
        public ?PaymentData $paymentAttributes = null,
        public ?CustomerNoteData $customerNoteAttributes = null,
    ) {}
}
