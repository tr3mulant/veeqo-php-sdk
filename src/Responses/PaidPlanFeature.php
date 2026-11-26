<?php

namespace TremulantTech\Veeqo\Responses;

readonly class PaidPlanFeature
{
    public function __construct(
        public string $name,
        public Condition $condition,
    ) {}
}
