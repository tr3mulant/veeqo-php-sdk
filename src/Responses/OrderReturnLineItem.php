<?php

namespace TremulantTech\Veeqo\Responses;

readonly class OrderReturnLineItem
{
    public function __construct(
        public int $id,
        public ?int $quantity = null,
        public ?int $receivedQuantity = null,
        public ?int $sellableId = null,
        public ?string $skuCode = null,
        public ?string $title = null,
    ) {}
}
