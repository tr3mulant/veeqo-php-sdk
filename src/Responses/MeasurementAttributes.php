<?php

namespace TremulantTech\Veeqo\Responses;

use TremulantTech\Veeqo\Enums\DimensionsUnit;

readonly class MeasurementAttributes
{
    public function __construct(
        public int $id,
        public float $depth,
        public DimensionsUnit $dimensionsUnit,
        public float $height,
        public float $width,
    ) {}
}
