<?php

namespace TremulantTech\Veeqo\Data\Orders;

readonly class AddressData
{
    /**
     * @param  string|null  $country  ISO-2 code.
     */
    public function __construct(
        public int $customerId,
        public ?string $address1 = null,
        public ?string $address2 = null,
        public ?string $city = null,
        public ?string $company = null,
        public ?string $country = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $phone = null,
        public ?string $state = null,
        public ?string $zip = null,
    ) {}
}
