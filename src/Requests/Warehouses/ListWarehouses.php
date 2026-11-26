<?php

namespace TremulantTech\Veeqo\Requests\Warehouses;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-warehouses/
 */
class ListWarehouses extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private readonly ?string $externalId = null) {}

    public function resolveEndpoint(): string
    {
        return '/warehouses';
    }

    protected function defaultQuery(): array
    {
        return $this->externalId === null ? [] : ['external_id' => $this->externalId];
    }
}
