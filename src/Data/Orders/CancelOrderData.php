<?php

namespace TremulantTech\Veeqo\Data\Orders;

readonly class CancelOrderData
{
    public function __construct(
        public ?bool $sendVeeqoEmail = null,
        public ?string $cancelReason = null,
    ) {}
}
