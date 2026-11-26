<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ChannelProduct
{
    /**
     * @param  list<ChannelSellable>  $channelSellables
     */
    public function __construct(
        public int $id,
        public ChannelSummary $channel,
        public array $channelSellables,
        public string $channelTypeCode,
        public string $remoteDescription,
        public string $remoteId,
        public string $remoteTitle,
        public string $status,
        public ?string $channelShortName = null,
    ) {}
}
