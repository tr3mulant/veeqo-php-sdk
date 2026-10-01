<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Warehouse Detail
 *
 * Update details about a warehouse.
 */
class UpdateWarehouseDetail extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/warehouses/{$this->id}";
	}


	/**
	 * @param int $id ID of the Warehouse
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
