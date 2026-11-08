<?php

namespace TremulantTech\Veeqo\Data\PurchaseOrderProductVariants;

readonly class UpdatePurchaseOrderProductVariantData
{
    /**
     * @param  numeric-string|null  $cost
     * @param  int|null  $receivedQuantity  Adjusts warehouse stock but not the line item's received count; to record a receipt, use purchaseOrders()->update() instead.
     */
    public function __construct(
        public ?int $quantity = null,
        public ?string $cost = null,
        public ?float $taxRate = null,
        public ?int $displayPosition = null,
        public ?string $supplierReferenceNumber = null,
        public ?string $supplierTitle = null,
        public ?int $receivedQuantity = null,
    ) {}
}
