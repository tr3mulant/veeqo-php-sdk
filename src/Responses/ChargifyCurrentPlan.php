<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ChargifyCurrentPlan
{
    public function __construct(
        public ?bool $available = null,
        public ?string $chargeType = null,
        public ?Features $features = null,
        public ?string $name = null,
        public ?string $planId = null,
        public ?string $pricing = null,
        public ?string $productHandle = null,
    ) {}
}
