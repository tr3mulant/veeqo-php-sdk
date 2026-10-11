<?php

namespace TremulantTech\Veeqo\Data\Orders;

final readonly class PaymentData
{
    public function __construct(
        public string $paymentType,
        public ?string $referenceNumber = null,
    ) {}
}
