<?php

namespace TremulantTech\Veeqo\Data\Customers;

use TremulantTech\Veeqo\Enums\CustomerType;

readonly class UpdateCustomerData
{
    /**
     * @param  string|null  $notes  Up to 255 characters; documented by the operation's example, not its schema.
     */
    public function __construct(
        public string $email,
        public ?CustomerType $customerType = null,
        public ?string $phone = null,
        public ?string $mobile = null,
        public ?string $notes = null,
        public ?AddressData $billingAddressAttributes = null,
    ) {}
}
