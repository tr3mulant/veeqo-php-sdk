<?php

namespace TremulantTech\Veeqo\Responses;

/**
 * Typed from Veeqo's doc examples only (ADR 0011).
 */
readonly class SellableSummary
{
    public function __construct(
        public int $id,
        public ?string $skuCode = null,
        public ?string $fullTitle = null,
    ) {}
}
