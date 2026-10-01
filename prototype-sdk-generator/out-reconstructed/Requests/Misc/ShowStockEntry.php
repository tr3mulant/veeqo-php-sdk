<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Show a Stock Entry
 */
class ShowStockEntry extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/sellables/{$this->sellableId}/warehouses/{$this->warehouseId}/stock_entry";
	}


	/**
	 * @param float|int $sellableId Sellable ID
	 * @param float|int $warehouseId Warehouse ID
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected float|int $sellableId,
		protected float|int $warehouseId,
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
