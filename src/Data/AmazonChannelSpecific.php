<?php

namespace TremulantTech\Veeqo\Data;

final readonly class AmazonChannelSpecific
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'mws_auth_token',
        'vat_calculation_service_enabled_at',
    ];

    public function __construct(
        public int $id,
        public string $sellerId,
        public string $marketplaceId,
        public int $channelId,
        public int $fbaChannelId,
    ) {}
}
