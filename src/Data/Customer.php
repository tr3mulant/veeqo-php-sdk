<?php

namespace TremulantTech\Veeqo\Data;

final readonly class Customer
{
    /** @internal Wire keys deliberately not mapped (ADR 0006 drift check). */
    public const array UNMAPPED = [
        'account',
        'company_name',
        'currency',
        'delivery_method',
        'delivery_method_id',
        'discount',
        'minimum_order_value',
        'mobile',
        'notes',
        'payment_method',
        'payment_terms',
        'phone',
        'price_list',
        'price_list_id',
        'remote_id',
    ];

    /**
     * @param list<ShippingAddress> $shippingAddresses
     */
    public function __construct(
        public int $id,
        public int $createdById,
        public string $customerType,
        public string $fullName,
        public BillingAddress $billingAddress,
        public array $shippingAddresses,
        public ContactData $contactData,
        public ShippingAddress $lastUsedShippingAddress,
        public ?string $email = null,
    ) {}
}
