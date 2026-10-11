<?php

namespace TremulantTech\Veeqo\Data;

final readonly class ContactData
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'phone',
    ];

    public function __construct(
        public ?string $email = null,
    ) {}
}
