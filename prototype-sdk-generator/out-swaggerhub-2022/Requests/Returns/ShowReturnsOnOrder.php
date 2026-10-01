<?php

namespace IronGate\Veeqo\Requests\Returns;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Show Returns on Order
 */
class ShowReturnsOnOrder extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/orders/{$this->orderId}/returns";
	}


	/**
	 * @param int $orderId ID of the Order
	 */
	public function __construct(
		protected int $orderId,
	) {
	}
}
