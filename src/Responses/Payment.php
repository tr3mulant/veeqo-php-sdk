<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Payment
{
    public function __construct(
        public int $id,
        public DateTimeImmutable $createdAt,
        public int $createdById,
        public int $orderId,
        public string $paymentType,
        public string $referenceNumber,
        public DateTimeImmutable $updatedAt,
    ) {}
}
