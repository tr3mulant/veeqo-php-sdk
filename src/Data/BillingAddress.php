<?php

namespace TremulantTech\Veeqo\Data;

final readonly class BillingAddress
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'is_default',
        'short_name',
    ];

    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $city,
        public string $country,
        public string $state,
        public string $zip,
        public ?string $address1 = null,
        public ?string $address2 = null,
        public ?string $company = null,
        public ?string $phone = null,
        public ?string $email = null,
    ) {}
}
