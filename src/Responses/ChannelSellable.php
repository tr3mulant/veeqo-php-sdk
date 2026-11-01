<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ChannelSellable
{
    /**
     * @param  numeric-string|null  $remotePrice
     */
    public function __construct(
        public int $id,
        public ?string $remoteTitle = null,
        public ?string $remoteSku = null,
        public ?string $remotePrice = null,
        public ?string $currencyCode = null,
        public ?int $remoteInventoryQuantity = null,
        public ?string $remoteId = null,
        public ?bool $active = null,
    ) {}
}
