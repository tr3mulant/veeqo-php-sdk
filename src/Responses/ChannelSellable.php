<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ChannelSellable
{
    /**
     * @param  numeric-string|null  $remotePrice
     * @param  numeric-string|null  $remoteProfit
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
        public ?ChannelSummary $channel = null,
        public ?int $channelProductId = null,
        public ?string $channelProductRemoteTitle = null,
        public ?string $channelProductStatus = null,
        public ?string $imageUrl = null,
        public ?string $remoteGrams = null,
        public ?float $remoteMargin = null,
        public ?string $remoteProfit = null,
        public ?int $sellableId = null,
        public ?string $url = null,
    ) {}
}
