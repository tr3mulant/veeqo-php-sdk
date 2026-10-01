<?php

namespace IronGate\Veeqo\Requests\Warehouses;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View Warehouse Detail
 */
class ViewWarehouseDetail extends Request
{
	protected Method $method = Method::GET;


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
