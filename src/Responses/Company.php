<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Company
{
    /**
     * @param  list<string>  $activeFeatures
     * @param  array<string, array<string, bool>>  $planFeatures  category => action => allowed
     * @param  list<User>  $employees
     * @param  array<string, list<PaidPlanFeature>>|null  $paidPlanFeatures  keyed by feature type (`CHANNEL`)
     */
    public function __construct(
        public int $id,
        public string $name,
        public bool $shopifyAppStoreReferral,
        public bool $trialing,
        public bool $paying,
        public bool $hasEverCreatedRemoteChannel,
        public bool $hasRightToUseVeeqo,
        public string $chargifyProductHandle,
        public array $activeFeatures,
        public string $veeqoProductName,
        public array $planFeatures,
        public User $owner,
        public array $employees,
        public CompanyAddress $companyAddress,
        public Setting $settings,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
        public ?DateTimeImmutable $trialEndDate = null,
        public ?DateTimeImmutable $billingPeriodStarted = null,
        public ?string $referringWebsite = null,
        public ?ChargifyCurrentPlan $chargifyCurrentPlan = null,
        public ?array $paidPlanFeatures = null,
    ) {}
}
