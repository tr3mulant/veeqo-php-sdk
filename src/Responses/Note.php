<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Note
{
    public function __construct(
        public int $id,
        public ?DateTimeImmutable $createdAt = null,
        public ?User $createdBy = null,
        public ?int $orderId = null,
        public ?string $text = null,
    ) {}
}
