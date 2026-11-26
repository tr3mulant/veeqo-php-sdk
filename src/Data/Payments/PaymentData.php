<?php

namespace TremulantTech\Veeqo\Data\Payments;

use TremulantTech\Veeqo\Enums\PaymentType;

readonly class PaymentData
{
    /**
     * @param  PaymentType|null  $paymentType  The spec types it `string` here; its values are the ones `POST /orders` documents for the same field.
     */
    public function __construct(
        public ?PaymentType $paymentType = null,
        public ?int $orderId = null,
        public ?string $referenceNumber = null,
    ) {}
}
