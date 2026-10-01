<?php

namespace IronGate\Veeqo\Requests\DeliveryMethods;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Delivery Method Detail
 */
class UpdateDeliveryMethodDetail extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/delivery_methods/{$this->id}";
	}


	/**
	 * @param int $id
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
