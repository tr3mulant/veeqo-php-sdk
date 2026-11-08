<?php

namespace TremulantTech\Veeqo\Responses;

readonly class CompanyAddress
{
    public function __construct(
        public string $addressLine1,
        public string $postCode,
        public string $city,
        public string $country,
        public ?string $addressLine2 = null,
        public ?string $addressLine3 = null,
        public ?string $region = null,
        public ?string $phone = null,
    ) {}
}
