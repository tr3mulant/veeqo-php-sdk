<?php

namespace TremulantTech\Veeqo\Data\Warehouses;

readonly class CutOffTimesData
{
    public function __construct(
        public ?CutOffTimesDefaultData $default = null,
    ) {}
}
