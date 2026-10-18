<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class User
{
    public function __construct(
        public int $id,
        public string $email,
        public ?int $accountOwnerUserId = null,
        public ?DateTimeImmutable $activatedAt = null,
        public ?string $activationCode = null,
        public ?string $apiKey = null,
        public ?CompanySummary $company = null,
        public ?int $companyId = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?int $createdById = null,
        public ?int $defaultWarehouseId = null,
        public ?DateTimeImmutable $deletedAt = null,
        public ?int $deletedById = null,
        public ?bool $guideCompletedMessageViewed = null,
        public ?string $guideProgress = null,
        public ?string $location = null,
        public ?string $login = null,
        public ?string $name = null,
        public ?bool $ordersWalkthroughViewed = null,
        public ?string $state = null,
        public ?DateTimeImmutable $updatedAt = null,
        public ?int $updatedById = null,
    ) {}
}
