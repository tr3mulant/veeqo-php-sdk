<?php

namespace TremulantTech\Veeqo\Data\Customers;

use TremulantTech\Veeqo\Enums\CustomerType;

readonly class CreateCustomerData
{
    /**
     * @param  string|null  $notes  Up to 255 characters.
     */
    public function __construct(
        public string $email,
        public CustomerType $customerType,
        public ?string $phone = null,
        public ?string $mobile = null,
        public ?string $notes = null,
        public ?AddressData $billingAddressAttributes = null,
    ) {}
}
