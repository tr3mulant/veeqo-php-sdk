<?php

namespace TremulantTech\Veeqo\Enums;

/**
 * The `state` a purchase order is created or updated with. Veeqo stores any other value, hiding the purchase order from every state filter.
 */
enum PurchaseOrderState: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Completed = 'completed';
}
