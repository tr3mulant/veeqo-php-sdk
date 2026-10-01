<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Delivery Method Detail
 *
 * Update the name or cost of a specific delivery method.
 */
class UpdateDeliveryMethodDetail extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/delivery_methods/{$this->id}";
	}


	/**
	 * @param int $id ID of the delivery method to retrieve.
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
