<?php

namespace IronGate\Veeqo\Requests\Warehouses;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Warehouse
 */
class DeleteWarehouse extends Request
{
	protected Method $method = Method::DELETE;


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
