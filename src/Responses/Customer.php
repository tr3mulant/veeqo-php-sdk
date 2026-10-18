<?php

namespace TremulantTech\Veeqo\Responses;

use TremulantTech\Veeqo\Enums\CustomerType;

readonly class Customer
{
    /**
     * @param  list<Address>  $shippingAddresses
     */
    public function __construct(
        public int $id,
        public Address $billingAddress,
        public int $createdById,
        public array $shippingAddresses,
        public ?ContactData $contactData = null,
        public ?CustomerType $customerType = null,
        public ?string $email = null,
        public ?string $fullName = null,
        public ?Address $lastUsedShippingAddress = null,
        public ?string $mobile = null,
        public ?string $notes = null,
        public ?string $phone = null,
    ) {}
}
