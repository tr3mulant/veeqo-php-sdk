<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class WarehouseSummary
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $addressLine1 = null,
        public ?string $addressLine2 = null,
        public ?string $city = null,
        public ?string $clickAndCollectDays = null,
        public ?bool $clickAndCollectEnabled = null,
        public ?string $country = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?int $createdById = null,
        public ?int $defaultMinReorder = null,
        public ?DateTimeImmutable $deletedAt = null,
        public ?int $deletedById = null,
        public ?int $displayPosition = null,
        public ?string $inventoryTypeCode = null,
        public ?string $phone = null,
        public ?string $postCode = null,
        public ?string $region = null,
        public ?DateTimeImmutable $updatedAt = null,
        public ?int $updatedById = null,
        public ?int $userId = null,
    ) {}
}
