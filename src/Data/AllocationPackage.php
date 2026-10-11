<?php

namespace TremulantTech\Veeqo\Data;

final readonly class AllocationPackage
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'package_id',
        'package_name',
        'package_provider',
    ];

    public function __construct(
        public int $id,
        public int $allocationId,
        public \DateTimeImmutable $createdAt,
        public float $depth,
        public float $height,
        public string $packageSelectionSource,
        public string $similarityKey,
        public \DateTimeImmutable $updatedAt,
        public float $weight,
        public float $width,
        public string $weightUnit,
        public string $dimensionsUnit,
    ) {}
}
