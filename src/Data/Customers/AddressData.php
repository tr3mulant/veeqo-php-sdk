<?php

namespace TremulantTech\Veeqo\Data\Customers;

readonly class AddressData
{
    /**
     * @param  string|null  $country  ISO-2 code.
     */
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $company = null,
        public ?string $address1 = null,
        public ?string $address2 = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $zip = null,
    ) {}
}
