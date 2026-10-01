<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View Delivery Method
 *
 * View details of a specific delivery method.
 */
class ViewDeliveryMethod extends Request
{
	protected Method $method = Method::GET;


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
