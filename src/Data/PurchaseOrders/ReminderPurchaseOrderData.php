<?php

namespace TremulantTech\Veeqo\Data\PurchaseOrders;

readonly class ReminderPurchaseOrderData
{
    /**
     * @param  list<int>  $ids  Only active purchase orders of the company are reminded; Veeqo skips the rest silently.
     */
    public function __construct(
        public array $ids,
    ) {}
}
