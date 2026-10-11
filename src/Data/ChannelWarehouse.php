<?php

namespace TremulantTech\Veeqo\Data;

final readonly class ChannelWarehouse
{
    public function __construct(
        public int $id,
        public bool $active,
        public int $rank,
        public Warehouse $warehouse,
    ) {}
}
