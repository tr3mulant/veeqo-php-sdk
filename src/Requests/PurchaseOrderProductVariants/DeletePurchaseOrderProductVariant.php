<?php

namespace TremulantTech\Veeqo\Requests\PurchaseOrderProductVariants;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-purchase-order-line-item/
 */
class DeletePurchaseOrderProductVariant extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $purchaseOrderId, protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/purchase_orders/{$this->purchaseOrderId}/purchase_order_product_variants/{$this->id}";
    }
}
