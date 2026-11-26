<?php

namespace TremulantTech\Veeqo\Requests\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-product-detail/
 */
class GetProduct extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/products/{$this->id}";
    }
}
