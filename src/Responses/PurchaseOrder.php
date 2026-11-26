<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

/**
 * Typed from Veeqo's doc examples only (ADR 0011).
 */
readonly class PurchaseOrder
{
    /**
     * @param  numeric-string|null  $currencyRate
     * @param  numeric-string|null  $subtotal
     * @param  numeric-string|null  $totalTax
     * @param  numeric-string|null  $totalExcludingTax
     * @param  numeric-string|null  $totalIncludingTax
     * @param  numeric-string|null  $shippingAndHandling
     * @param  list<PurchaseOrderProductVariant>|null  $purchaseOrderProductVariants
     */
    public function __construct(
        public int $id,
        public ?string $number = null,
        public ?string $referenceNumber = null,
        public ?string $state = null,
        public ?int $supplierId = null,
        public ?int $destinationWarehouseId = null,
        public ?int $userId = null,
        public ?int $createdById = null,
        public ?int $updatedById = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?DateTimeImmutable $updatedAt = null,
        public ?DateTimeImmutable $expectedDate = null,
        public ?int $estimatedDeliveryDays = null,
        public ?string $currencyCode = null,
        public ?string $currencyRate = null,
        public ?string $note = null,
        public ?int $unitsOrdered = null,
        public ?int $unitsReceived = null,
        public ?int $productVariantsCount = null,
        public ?int $receivedProductVariantsCount = null,
        public ?string $subtotal = null,
        public ?string $totalTax = null,
        public ?string $totalExcludingTax = null,
        public ?string $totalIncludingTax = null,
        public ?string $shippingAndHandling = null,
        public ?string $supplierReportFormat = null,
        public ?DateTimeImmutable $sentAt = null,
        public ?DateTimeImmutable $receivedAt = null,
        public ?SupplierSummary $supplier = null,
        public ?WarehouseSummary $destinationWarehouse = null,
        public ?User $createdBy = null,
        public ?array $purchaseOrderProductVariants = null,
    ) {}
}
