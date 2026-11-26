<?php

namespace TremulantTech\Veeqo\Requests\Suppliers;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-suppliers/
 */
class ListSuppliers extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/suppliers';
    }
}
