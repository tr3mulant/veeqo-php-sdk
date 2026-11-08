<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

/**
 * Typed from Veeqo's doc examples only (ADR 0011).
 */
readonly class PurchaseOrderProductVariant
{
    /**
     * @param  numeric-string|null  $cost
     * @param  numeric-string|null  $taxRate
     * @param  numeric-string|null  $totalAmountExcludingTax
     * @param  numeric-string|null  $totalAmountIncludingTax
     */
    public function __construct(
        public int $id,
        public ?int $purchaseOrderId = null,
        public ?int $productVariantId = null,
        public ?int $supplierId = null,
        public ?int $quantity = null,
        public ?int $received = null,
        public ?DateTimeImmutable $receivedAt = null,
        public ?string $cost = null,
        public ?string $taxRate = null,
        public ?string $totalAmountExcludingTax = null,
        public ?string $totalAmountIncludingTax = null,
        public ?bool $shipped = null,
        public ?SupplierProductVariant $supplierProductVariant = null,
    ) {}
}
