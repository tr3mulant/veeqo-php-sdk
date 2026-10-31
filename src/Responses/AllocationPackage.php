<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;
use TremulantTech\Veeqo\Enums\DimensionsUnit;

readonly class AllocationPackage
{
    public function __construct(
        public int $id,
        public int $allocationId,
        public DateTimeImmutable $createdAt,
        public float $depth,
        public DimensionsUnit $dimensionsUnit,
        public float $height,
        public string $packageSelectionSource,
        public string $similarityKey,
        public DateTimeImmutable $updatedAt,
        public float $weight,
        public string $weightUnit,
        public float $width,
        public ?string $packageId = null,
        public ?string $packageProvider = null,
    ) {}
}
