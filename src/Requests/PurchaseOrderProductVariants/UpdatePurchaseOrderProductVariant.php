<?php

namespace TremulantTech\Veeqo\Requests\PurchaseOrderProductVariants;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\PurchaseOrderProductVariants\UpdatePurchaseOrderProductVariantData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-purchase-order-line-item/
 */
class UpdatePurchaseOrderProductVariant extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $purchaseOrderId, protected int $id, protected UpdatePurchaseOrderProductVariantData $data) {}

    public function resolveEndpoint(): string
    {
        return "/purchase_orders/{$this->purchaseOrderId}/purchase_order_product_variants/{$this->id}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['purchase_order_product_variant' => Veeqo::normalize($this->data)];
    }
}
