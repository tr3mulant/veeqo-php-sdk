<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Payment
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'card_number',
    ];

    public function __construct(
        public int $id,
        public string $paymentType,
        public string $referenceNumber,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public int $orderId,
        public int $createdById,
    ) {}
}
