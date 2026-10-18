<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Address
{
    public function __construct(
        public int $id,
        public string $city,
        public string $country,
        public string $firstName,
        public string $lastName,
        public string $zip,
        public ?string $address1 = null,
        public ?string $address2 = null,
        public ?string $company = null,
        public ?string $email = null,
        public ?bool $locationFound = null,
        public ?string $phone = null,
        public ?string $state = null,
        public ?bool $verified = null,
    ) {}
}
