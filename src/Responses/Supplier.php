<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

/**
 * Unverified: typed from Veeqo's doc examples only, as suppliers are plan-gated (ADR 0011).
 */
readonly class Supplier
{
    /**
     * @param  numeric-string|null  $creditLimit
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $addressLine1 = null,
        public ?string $addressLine2 = null,
        public ?string $city = null,
        public ?string $region = null,
        public ?string $country = null,
        public ?string $postCode = null,
        public ?string $salesContactName = null,
        public ?string $salesContactEmail = null,
        public ?string $salesPhoneNumber = null,
        public ?string $accountingContactName = null,
        public ?string $accountingContactEmail = null,
        public ?string $accountingPhoneNumber = null,
        public ?string $currencyCode = null,
        public ?int $createdById = null,
        public ?int $updatedById = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?DateTimeImmutable $updatedAt = null,
        public ?string $bankName = null,
        public ?string $bankAccountNumber = null,
        public ?string $bankSortCode = null,
        public ?string $creditLimit = null,
        public ?int $activePurchaseOrderCount = null,
        public ?int $completedPurchaseOrderCount = null,
        public ?string $purchaseOrderTemplate = null,
        public ?string $reminderEmailTemplate = null,
    ) {}
}
