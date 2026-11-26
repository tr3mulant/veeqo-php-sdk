<?php

namespace TremulantTech\Veeqo\Requests\Warehouses;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-warehouse/
 */
class DeleteWarehouse extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/warehouses/{$this->id}";
    }
}
