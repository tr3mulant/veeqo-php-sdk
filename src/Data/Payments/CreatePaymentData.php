<?php

namespace TremulantTech\Veeqo\Data\Payments;

readonly class CreatePaymentData
{
    /**
     * @param  numeric-string|null  $amount
     */
    public function __construct(
        public ?PaymentData $paymentAttributes = null,
        public ?string $amount = null,
    ) {}
}
