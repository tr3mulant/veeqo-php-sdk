<?php

namespace IronGate\Veeqo\Requests\DeliveryMethods;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View Delivery Method Detail
 */
class ViewDeliveryMethodDetail extends Request
{
	protected Method $method = Method::GET;


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
