<?php

namespace TremulantTech\Veeqo\Responses;

readonly class LinkedAccount
{
    public function __construct(
        public ?int $shippingConfigurationId = null,
        public ?string $carrier = null,
    ) {}
}
