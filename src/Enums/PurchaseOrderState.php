<?php

namespace TremulantTech\Veeqo\Enums;

/**
 * The `state` filter on GET /purchase_orders. The received-status values and `All` are filter-only; the response's `state` stays a string (ADR 0007).
 */
enum PurchaseOrderState: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Completed = 'completed';
    case NotReceived = 'not_received';
    case PartiallyReceived = 'partially_received';
    case FullyReceived = 'fully_received';
    case PastDue = 'past_due';
    case All = 'all';
}
