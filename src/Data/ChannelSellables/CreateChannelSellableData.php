<?php

namespace TremulantTech\Veeqo\Data\ChannelSellables;

readonly class CreateChannelSellableData
{
    /**
     * @param  ChannelProductData|null  $channelProductAttributes  Creates a new channel product for the sellable.
     * @param  int|null  $channelProductId  Links an existing channel product instead; sent as the `channel_product` relationship.
     */
    public function __construct(
        public int $sellableId,
        public int $channelId,
        public string $remoteId,
        public ?string $remoteSku = null,
        public ?string $remoteTitle = null,
        public ?ChannelProductData $channelProductAttributes = null,
        public ?int $channelProductId = null,
    ) {}
}
