<?php

namespace TremulantTech\Veeqo\Data;

final readonly class MeasurementAttributes
{
    public function __construct(
        public int $id,
        public float $width,
        public float $height,
        public float $depth,
        public string $dimensionsUnit,
    ) {}
}
