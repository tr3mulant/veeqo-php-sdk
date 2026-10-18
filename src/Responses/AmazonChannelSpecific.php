<?php

namespace TremulantTech\Veeqo\Responses;

readonly class AmazonChannelSpecific
{
    public function __construct(
        public int $id,
        public int $channelId,
        public int $fbaChannelId,
        public string $marketplaceId,
        public string $sellerId,
    ) {}
}
