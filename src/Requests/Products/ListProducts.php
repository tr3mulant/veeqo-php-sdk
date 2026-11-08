<?php

namespace TremulantTech\Veeqo\Requests\Products;

use DateTimeInterface;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-products/
 */
class ListProducts extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /** @var array<string, int|string> */
    private readonly array $filters;

    public function __construct(
        ?int $sinceId = null,
        ?int $warehouseId = null,
        ?DateTimeInterface $createdAtMin = null,
        ?DateTimeInterface $updatedAtMin = null,
        ?string $query = null,
    ) {
        $this->filters = array_filter([
            'since_id' => $sinceId,
            'warehouse_id' => $warehouseId,
            'created_at_min' => $createdAtMin?->format(DATE_ATOM),
            'updated_at_min' => $updatedAtMin?->format(DATE_ATOM),
            'query' => $query,
        ], fn (mixed $value) => $value !== null);
    }

    public function resolveEndpoint(): string
    {
        return '/products';
    }

    protected function defaultQuery(): array
    {
        return $this->filters;
    }
}
