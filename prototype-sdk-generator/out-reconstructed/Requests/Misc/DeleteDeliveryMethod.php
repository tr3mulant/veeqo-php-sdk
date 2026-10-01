<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Delivery Method
 *
 * Delete a delivery method by its ID.
 */
class DeleteDeliveryMethod extends Request
{
	protected Method $method = Method::DELETE;


	public function resolveEndpoint(): string
	{
		return "/delivery_methods/{$this->id}";
	}


	/**
	 * @param int $id ID of the delivery method to delete.
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
