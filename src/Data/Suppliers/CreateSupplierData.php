<?php

namespace TremulantTech\Veeqo\Data\Suppliers;

/**
 * The spec's body schema repeats the response's server-managed keys (`id`, `created_*`, `updated_*`, purchase order counts);
 * they are left out. `country` is in the doc example though not the schema.
 */
readonly class CreateSupplierData
{
    /**
     * @param  numeric-string|null  $creditLimit
     */
    public function __construct(
        public string $name,
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
