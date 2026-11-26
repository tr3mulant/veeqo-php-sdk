<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ChannelSummary
{
    public function __construct(
        public int $id,
        public string $name,
        public string $typeCode,
        public ?string $currencyCode = null,
        public ?string $marketplaceCountry = null,
        public ?string $shortName = null,
        public ?bool $veeqoDictatesPrice = null,
    ) {}
}
