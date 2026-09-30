<?php

namespace IronGate\Veeqo\Requests\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-orders/
 */
class ListOrders extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $filters  e.g. ['status' => 'awaiting_fulfillment', 'since_id' => 123]
     */
    public function __construct(protected array $filters = []) {}

    public function resolveEndpoint(): string
    {
        return '/orders';
    }

    protected function defaultQuery(): array
    {
        return $this->filters;
    }
}
