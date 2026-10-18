<?php

namespace TremulantTech\Veeqo\Responses;

readonly class DeliveryMethodSummary
{
    /**
     * @param  numeric-string  $cost
     */
    public function __construct(
        public int $id,
        public string $cost,
        public string $name,
        public ?int $userId = null,
    ) {}
}
