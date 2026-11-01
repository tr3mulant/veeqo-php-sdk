<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class DeliveryMethod
{
    /**
     * @param  numeric-string  $cost
     */
    public function __construct(
        public int $id,
        public string $cost,
        public string $name,
        public int $userId,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {}
}
