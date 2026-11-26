<?php

namespace TremulantTech\Veeqo\Requests\Suppliers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-a-supplier-detail/
 */
class GetSupplier extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/suppliers/{$this->id}";
    }
}
