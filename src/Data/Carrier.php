<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Carrier
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'provider_type',
        'shipping_service_options',
        'slug',
    ];

    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public string $name,
        public bool $isConfigured,
        public bool $isIntegrated,
        public bool $showWhenShipping,
        public bool $inboundSupported,
        public bool $requiresReconnect,
        public bool $usingDhlParcelRestApi,
    ) {}
}
