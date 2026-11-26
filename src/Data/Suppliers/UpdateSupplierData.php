<?php

namespace TremulantTech\Veeqo\Data\Suppliers;

/**
 * Same keys as {@see CreateSupplierData}, none required.
 */
readonly class UpdateSupplierData
{
    /**
     * @param  numeric-string|null  $creditLimit
     */
    public function __construct(
        public ?string $name = null,
        public ?string $addressLine1 = null,
        public ?string $addressLine2 = null,
        public ?string $city = null,
        public ?string $region = null,
        public ?string $country = null,
        public ?string $postCode = null,
        public ?string $salesContactName = null,
        public ?string $salesContactEmail = null,
        public ?string $salesPhoneNumber = null,
        public ?string $accountingContactName = null,
        public ?string $accountingContactEmail = null,
        public ?string $accountingPhoneNumber = null,
        public ?string $currencyCode = null,
        public ?string $bankName = null,
        public ?string $bankAccountNumber = null,
        public ?string $bankSortCode = null,
        public ?string $creditLimit = null,
        public ?string $purchaseOrderTemplate = null,
        public ?string $reminderEmailTemplate = null,
    ) {}
}
