<?php

namespace IronGate\Veeqo\Requests\Orders;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Order Detail
 */
class UpdateOrderDetail extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/orders/{$this->orderId}";
	}


	/**
	 * @param int $orderId ID of the Order
	 */
	public function __construct(
		protected int $orderId,
	) {
	}
}
