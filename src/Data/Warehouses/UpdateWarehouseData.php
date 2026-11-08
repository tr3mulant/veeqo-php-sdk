<?php

namespace TremulantTech\Veeqo\Data\Warehouses;

readonly class UpdateWarehouseData
{
    public function __construct(
        public ?string $name = null,
        public ?string $addressLine1 = null,
        public ?string $addressLine2 = null,
        public ?string $city = null,
        public ?string $region = null,
        public ?string $country = null,
        public ?string $postCode = null,
        public ?string $phone = null,
        public ?bool $cutOffTimesEnabled = null,
        public ?CutOffTimesData $cutOffTimes = null,
    ) {}
}
