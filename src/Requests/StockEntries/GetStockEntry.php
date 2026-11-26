<?php

namespace TremulantTech\Veeqo\Requests\StockEntries;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/show-a-stock-entry/
 */
class GetStockEntry extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $sellableId, protected int $warehouseId) {}

    public function resolveEndpoint(): string
    {
        return "/sellables/{$this->sellableId}/warehouses/{$this->warehouseId}/stock_entry";
    }
}
