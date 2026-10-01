<?php

namespace IronGate\Veeqo\Requests\StockEntries;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update a Stock Entry
 */
class UpdateStockEntry extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/sellables/{$this->sellableId}/warehouses/{$this->warehouseId}/stock_entry";
	}


	/**
	 * @param float|int $sellableId Sellable ID
	 * @param float|int $warehouseId Warehouse ID
	 */
	public function __construct(
		protected float|int $sellableId,
		protected float|int $warehouseId,
	) {
	}
}
