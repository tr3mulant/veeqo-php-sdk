<?php

namespace TremulantTech\Veeqo\Responses;

readonly class DeliveryMethodSummary
{
    public function __construct(
        public int $id,
        /** @var numeric-string */
        public string $cost,
        public string $name,
        public ?int $userId = null,
    ) {}
}
