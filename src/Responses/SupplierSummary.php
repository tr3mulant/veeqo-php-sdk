<?php

namespace TremulantTech\Veeqo\Responses;

/**
 * Typed from Veeqo's doc examples only (ADR 0011).
 */
readonly class SupplierSummary
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $salesContactEmail = null,
        public ?string $currencyCode = null,
    ) {}
}
