<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Setting
{
    public function __construct(
        public ?string $currencyCode = null,
        public ?string $phoneNumber = null,
    ) {}
}
