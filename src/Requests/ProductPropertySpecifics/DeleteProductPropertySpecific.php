<?php

namespace TremulantTech\Veeqo\Requests\ProductPropertySpecifics;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/remove-property-from-product/
 */
class DeleteProductPropertySpecific extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $productId, protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/products/{$this->productId}/product_property_specifics/{$this->id}";
    }
}
