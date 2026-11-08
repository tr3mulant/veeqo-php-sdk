<?php

namespace TremulantTech\Veeqo\Requests\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-a-product/
 */
class DeleteProduct extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/products/{$this->id}";
    }
}
