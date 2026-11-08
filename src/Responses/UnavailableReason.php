<?php

namespace TremulantTech\Veeqo\Responses;

readonly class UnavailableReason
{
    public function __construct(
        public ?string $message = null,
    ) {}
}
