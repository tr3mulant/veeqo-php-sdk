<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class CompanySummary
{
    public function __construct(
        public int $id,
        public ?bool $cardValid = null,
        public ?ChargifyCurrentPlan $chargifyCurrentPlan = null,
        public ?string $chargifyProductHandle = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?bool $hasRightToUseVeeqo = null,
        public ?string $name = null,
        public ?string $referringWebsite = null,
        public ?Setting $setting = null,
        public ?SubscriptionPlan $subscriptionPlan = null,
        public ?int $subscriptionPlanId = null,
        public ?string $subscriptionStatus = null,
        public ?DateTimeImmutable $updatedAt = null,
    ) {}
}
