<?php

namespace TremulantTech\Veeqo\Data;

final readonly class ShippingAddress
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'email',
        'is_default',
        'residential',
        'short_name',
        'tax_id',
        'validated',
        'validation_message',
    ];

    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $city,
        public string $country,
        public string $state,
        public string $zip,
        public bool $verified,
        public bool $locationFound,
        public ?string $company = null,
        public ?string $address1 = null,
        public ?string $address2 = null,
        public ?string $phone = null,
    ) {}
}
