<?php

namespace TremulantTech\Veeqo\Data\Orders;

use TremulantTech\Veeqo\Enums\PaymentType;

readonly class PaymentData
{
    public function __construct(
        public PaymentType $paymentType,
        public ?string $referenceNumber = null,
    ) {}
}
