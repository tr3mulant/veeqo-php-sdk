<?php

namespace TremulantTech\Veeqo\Requests\PurchaseOrders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/get-purchase-order/
 */
class GetPurchaseOrder extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/purchase_orders/{$this->id}";
    }
}
