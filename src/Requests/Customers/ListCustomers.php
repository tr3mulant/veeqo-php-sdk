<?php

namespace TremulantTech\Veeqo\Requests\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use TremulantTech\Veeqo\Enums\CustomerType;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-customers/
 */
class ListCustomers extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /** @var array<string, string> */
    private readonly array $filters;

    public function __construct(?string $query = null, ?CustomerType $customerType = null)
    {
        $this->filters = array_filter(['query' => $query, 'customer_type' => $customerType?->value], fn (?string $value) => $value !== null);
    }

    public function resolveEndpoint(): string
    {
        return '/customers';
    }

    protected function defaultQuery(): array
    {
        return $this->filters;
    }
}
