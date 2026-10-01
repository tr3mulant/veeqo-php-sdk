<?php

namespace IronGate\Veeqo\Requests\Warehouses;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Warehouse Detail
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
