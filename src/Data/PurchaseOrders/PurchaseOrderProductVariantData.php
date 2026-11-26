<?php

namespace TremulantTech\Veeqo\Data\PurchaseOrders;

/**
 * A line item: edited by `id`, added by leaving `id` out. There is no `_destroy`; use purchaseOrderProductVariants()->delete().
 */
readonly class PurchaseOrderProductVariantData
{
    /**
     * @param  int|null  $productVariantId  The sellable id.
     * @param  numeric-string|null  $cost  Unit cost.
     * @param  int|null  $receivedQuantity  A delta added to the received count and to warehouse stock, not a target, so never resend it. Ignored on create.
     */
    public function __construct(
        public ?int $id = null,
        public ?int $productVariantId = null,
        public ?int $quantity = null,
        public ?string $cost = null,
        public ?float $taxRate = null,
        public ?int $displayPosition = null,
        public ?bool $shipped = null,
        public ?string $supplierReferenceNumber = null,
        public ?string $supplierTitle = null,
        public ?int $receivedQuantity = null,
    ) {}
}
