<?php

namespace TremulantTech\Veeqo\Responses;

/**
 * Typed from Veeqo's doc examples only (ADR 0011).
 */
readonly class SupplierProductVariant
{
    /**
     * @param  numeric-string|null  $cost
     */
    public function __construct(
        public int $id,
        public ?int $supplierId = null,
        public ?int $productVariantId = null,
        public ?string $cost = null,
        public ?string $title = null,
        public ?SellableSummary $productVariant = null,
    ) {}
}
