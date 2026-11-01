<?php

namespace TremulantTech\Veeqo\Data\Channels;

readonly class UpdateChannelData
{
    public function __construct(
        public ?string $name = null,
    ) {}
}
