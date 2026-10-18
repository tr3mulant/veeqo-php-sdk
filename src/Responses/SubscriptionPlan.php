<?php

namespace TremulantTech\Veeqo\Responses;

readonly class SubscriptionPlan
{
    public function __construct(
        public int $id,
        public ?string $billingInterval = null,
        public ?string $name = null,
        public ?string $stripePlanId = null,
    ) {}
}
