<?php

namespace TremulantTech\Veeqo\Data\ChannelSellables;

readonly class ChannelProductData
{
    public function __construct(
        public ?string $remoteTitle = null,
    ) {}
}
