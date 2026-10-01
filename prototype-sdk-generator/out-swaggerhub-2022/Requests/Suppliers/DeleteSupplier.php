<?php

namespace IronGate\Veeqo\Requests\Suppliers;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Supplier
 */
class DeleteSupplier extends Request
{
	protected Method $method = Method::DELETE;


	public function resolveEndpoint(): string
	{
		return "/suppliers/{$this->id}";
	}


	/**
	 * @param int $id ID of the Supplier
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
