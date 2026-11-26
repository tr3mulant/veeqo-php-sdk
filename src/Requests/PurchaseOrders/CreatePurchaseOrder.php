<?php

namespace TremulantTech\Veeqo\Requests\PurchaseOrders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\PurchaseOrders\CreatePurchaseOrderData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/create-purchase-order/
 */
class CreatePurchaseOrder extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreatePurchaseOrderData $data) {}

    public function resolveEndpoint(): string
    {
        return '/purchase_orders';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['purchase_order' => Veeqo::normalize($this->data)];
    }
}
