<?php

namespace TremulantTech\Veeqo\Data\Channels;

use TremulantTech\Veeqo\Enums\ChannelTypeCode;

readonly class CreateChannelData
{
    /**
     * @param  string|null  $currencyCode  A string: the spec's `GBP`-only enum is contradicted by its own `ISK` example.
     */
    public function __construct(
        public string $name,
        public ChannelTypeCode $typeCode,
        public ?string $currencyCode = null,
        public ?int $defaultWarehouseId = null,
        public ?string $shortName = null,
        public ?string $email = null,
        public ?bool $veeqoDictatesStockLevel = null,
    ) {}
}
