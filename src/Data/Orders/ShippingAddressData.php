<?php

namespace TremulantTech\Veeqo\Data\Orders;

final readonly class ShippingAddressData
{
    public function __construct(
        public int $customerId,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $company = null,
        public ?string $address1 = null,
        public ?string $address2 = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $zip = null,
        public ?string $country = null,
        public ?string $phone = null,
    ) {}
}
