<?php

namespace TremulantTech\Veeqo\Data\Warehouses;

readonly class CreateWarehouseData
{
    public function __construct(
        public string $name,
        public string $addressLine1,
        public string $city,
        public string $country,
        public string $postCode,
        public ?string $addressLine2 = null,
        public ?string $region = null,
        public ?string $phone = null,
        public ?bool $cutOffTimesEnabled = null,
        public ?CutOffTimesData $cutOffTimes = null,
    ) {}
}
