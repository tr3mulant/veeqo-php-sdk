<?php

namespace TremulantTech\Veeqo\Data\AllocationPackages;

use TremulantTech\Veeqo\Enums\PackageProvider;

/**
 * Weight and dimensions are in the package's existing `weight_unit` and `dimensions_unit`, which this can't change.
 */
readonly class UpdateAllocationPackageData
{
    /**
     * @param  string|null  $packageId  A string: carrier packages have ids like `fedex/envelope`.
     * @param  bool|null  $saveForSimilarShipments  Sent beside the `allocation_package` envelope, not inside it.
     */
    public function __construct(
        public ?float $weight = null,
        public ?float $width = null,
        public ?float $height = null,
        public ?float $depth = null,
        public ?PackageProvider $packageProvider = null,
        public ?string $packageId = null,
        public ?bool $saveForSimilarShipments = null,
    ) {}
}
