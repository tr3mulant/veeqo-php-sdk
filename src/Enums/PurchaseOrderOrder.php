<?php

namespace TremulantTech\Veeqo\Enums;

/**
 * The `order` (sort direction) filter on GET /purchase_orders.
 */
enum PurchaseOrderOrder: string
{
    case Asc = 'asc';
    case Desc = 'desc';
}
