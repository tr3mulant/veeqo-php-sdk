<?php

namespace TremulantTech\Veeqo\Enums;

/**
 * The `status` filter on GET /orders. The response's `status` stays a string: Veeqo returns undocumented statuses (ADR 0007).
 */
enum OrderStatus: string
{
    case AwaitingPayment = 'awaiting_payment';
    case AwaitingStock = 'awaiting_stock';
    case AwaitingFulfillment = 'awaiting_fulfillment';
    case Shipped = 'shipped';
    case OnHold = 'on_hold';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';
}
