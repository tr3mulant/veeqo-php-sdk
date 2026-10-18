<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ContactData
{
    public function __construct(
        public ?string $email = null,
    ) {}
}
