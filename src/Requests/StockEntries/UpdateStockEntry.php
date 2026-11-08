<?php

namespace TremulantTech\Veeqo\Requests\StockEntries;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\StockEntries\UpdateStockEntryData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-a-stock-entry/
 */
class UpdateStockEntry extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $sellableId, protected int $warehouseId, protected UpdateStockEntryData $data) {}

    public function resolveEndpoint(): string
    {
        return "/sellables/{$this->sellableId}/warehouses/{$this->warehouseId}/stock_entry";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['stock_entry' => Veeqo::normalize($this->data)];
    }
}
