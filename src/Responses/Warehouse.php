<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Warehouse
{
    public function __construct(
        public int $id,
        public string $addressLine1,
        public string $city,
        public string $name,
        public string $postCode,
        public string $region,
        public ?string $addressLine2 = null,
        public ?string $cachedTimeZone = null,
        public ?string $clickAndCollectDays = null,
        public ?bool $clickAndCollectEnabled = null,
        public ?int $companyId = null,
        public ?string $country = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?int $createdById = null,
        public ?bool $cutOffTimesEnabled = null,
        public ?int $defaultMinReorder = null,
        public ?DateTimeImmutable $deletedAt = null,
        public ?int $deletedById = null,
        public ?int $displayPosition = null,
        public ?string $eoriId = null,
        public ?string $externalId = null,
        public ?string $inventoryTypeCode = null,
        public ?string $iossId = null,
        public ?string $lat = null,
        public ?string $letterboxAddressId = null,
        public ?string $letterboxAddressStatus = null,
        public ?string $lng = null,
        public ?string $phone = null,
        public ?string $sortinghatWarehouseId = null,
        public ?string $taxId = null,
        public ?string $tradingName = null,
        public ?string $ukImsId = null,
        public ?DateTimeImmutable $updatedAt = null,
        public ?int $updatedById = null,
        public ?int $userId = null,
    ) {}
}
