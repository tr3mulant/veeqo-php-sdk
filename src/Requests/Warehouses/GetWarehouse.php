<?php

namespace TremulantTech\Veeqo\Requests\Warehouses;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-warehouse-detail/
 */
class GetWarehouse extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/warehouses/{$this->id}";
    }
}
