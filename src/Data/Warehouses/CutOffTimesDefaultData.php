<?php

namespace TremulantTech\Veeqo\Data\Warehouses;

/**
 * Each day is a `HH:MM` time, or `''` for no cut-off that day.
 */
readonly class CutOffTimesDefaultData
{
    public function __construct(
        public ?string $monday = null,
        public ?string $tuesday = null,
        public ?string $wednesday = null,
        public ?string $thursday = null,
        public ?string $friday = null,
        public ?string $saturday = null,
        public ?string $sunday = null,
    ) {}
}
