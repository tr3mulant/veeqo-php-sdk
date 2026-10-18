<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Carrier
{
    public function __construct(
        public int $id,
        public string $name,
        public ?DateTimeImmutable $createdAt = null,
        public ?bool $inboundSupported = null,
        public ?bool $isConfigured = null,
        public ?bool $isIntegrated = null,
        public ?bool $requiresReconnect = null,
        public ?bool $showWhenShipping = null,
        public ?DateTimeImmutable $updatedAt = null,
        public ?bool $usingDhlParcelRestApi = null,
    ) {}
}
