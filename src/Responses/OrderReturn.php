<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

/**
 * Not `Return`: that's a reserved word in PHP.
 */
readonly class OrderReturn
{
    public function __construct(
        public int $id,
        public ?DateTimeImmutable $createdAt = null,
        public ?string $number = null,
        public ?string $status = null,
        public ?string $type = null,
        public ?User $user = null,
        public ?string $warehouseName = null,
        /** @var list<OrderReturnLineItem> */
        public array $lineItems = [],
    ) {}
}
