<?php

namespace TremulantTech\Veeqo\Data\PurchaseOrders;

use DateTimeInterface;

/**
 * `send_to_supplier` is left out: the spec documents it as a dead field (use $sendSupplierReportEmail).
 */
readonly class CreatePurchaseOrderData
{
    /**
     * @param  string|null  $state  `draft`, `active` or `completed`.
     * @param  numeric-string|null  $shippingAndHandling
     * @param  bool|null  $sendSupplierReportEmail  Only emails when the purchase order is active, and Veeqo may suppress it silently.
     * @param  string|null  $supplierReportFormat  `pdf` or `csv` attaches the document; anything else sends no attachment.
     * @param  list<PurchaseOrderProductVariantData>|null  $purchaseOrderProductVariantsAttributes
     */
    public function __construct(
        public int $supplierId,
        public int $destinationWarehouseId,
        public ?string $state = null,
        public ?string $number = null,
        public ?string $referenceNumber = null,
        public ?DateTimeInterface $expectedDate = null,
        public ?string $currencyCode = null,
        public ?float $currencyRate = null,
        public ?int $estimatedDeliveryDays = null,
        public ?string $note = null,
        public ?string $shippingAndHandling = null,
        public ?bool $sendSupplierReportEmail = null,
        public ?string $supplierReportFormat = null,
        public ?string $shippingAddressLine1 = null,
        public ?string $shippingAddressLine2 = null,
        public ?string $shippingAddressTown = null,
        public ?string $shippingAddressState = null,
        public ?string $shippingAddressPostcode = null,
        public ?string $shippingAddressCountry = null,
        public ?string $billingAddressLine1 = null,
        public ?string $billingAddressLine2 = null,
        public ?string $billingAddressTown = null,
        public ?string $billingAddressState = null,
        public ?string $billingAddressPostcode = null,
        public ?string $billingAddressCountry = null,
        public ?array $purchaseOrderProductVariantsAttributes = null,
    ) {}
}
