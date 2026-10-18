<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ChannelWarehouse
{
    public function __construct(
        public int $rank,
        public Warehouse $warehouse,
        public ?int $id = null,
        public ?bool $active = null,
    ) {}
}
