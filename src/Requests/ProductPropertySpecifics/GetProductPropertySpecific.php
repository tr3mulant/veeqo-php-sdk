<?php

namespace TremulantTech\Veeqo\Requests\ProductPropertySpecifics;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-properties/
 */
class GetProductPropertySpecific extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $productId, protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/products/{$this->productId}/product_property_specifics/{$this->id}";
    }
}
