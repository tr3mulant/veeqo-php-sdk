<?php

namespace TremulantTech\Veeqo\Requests\PurchaseOrders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\PurchaseOrders\UpdatePurchaseOrderData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-purchase-order/
 */
class UpdatePurchaseOrder extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $id, protected UpdatePurchaseOrderData $data) {}

    public function resolveEndpoint(): string
    {
        return "/purchase_orders/{$this->id}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['purchase_order' => Veeqo::normalize($this->data)];
    }
}
