<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Warehouse
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'click_and_collect_days',
        'cut_off_times',
        'deleted_at',
        'deleted_by_id',
        'eori_id',
        'external_id',
        'ioss_id',
        'requested_carrier_account',
        'tax_id',
        'uk_ims_id',
        'updated_by_id',
        'user_id',
    ];

    public function __construct(
        public int $id,
        public string $name,
        public ?string $addressLine1 = null,
        public ?string $addressLine2 = null,
        public ?string $city = null,
        public ?string $region = null,
        public ?string $country = null,
        public ?string $postCode = null,
        public ?string $inventoryTypeCode = null,
        public ?int $defaultMinReorder = null,
        public ?bool $clickAndCollectEnabled = null,
        public ?int $createdById = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
        public ?string $phone = null,
        public ?int $displayPosition = null,
        public ?string $lat = null,
        public ?string $lng = null,
        public ?string $tradingName = null,
        public ?int $companyId = null,
        public ?string $letterboxAddressId = null,
        public ?string $letterboxAddressStatus = null,
        public ?bool $cutOffTimesEnabled = null,
        public ?string $sortinghatWarehouseId = null,
        public ?string $cachedTimeZone = null,
    ) {}
}
