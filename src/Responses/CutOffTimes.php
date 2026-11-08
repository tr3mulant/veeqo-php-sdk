<?php

namespace TremulantTech\Veeqo\Responses;

readonly class CutOffTimes
{
    public function __construct(
        public ?CutOffTimesDefault $default = null,
    ) {}
}
