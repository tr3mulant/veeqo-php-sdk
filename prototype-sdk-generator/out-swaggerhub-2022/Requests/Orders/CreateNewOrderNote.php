<?php

namespace IronGate\Veeqo\Requests\Orders;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a New Order Note
 */
class CreateNewOrderNote extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/orders/{$this->orderId}/notes";
	}


	/**
	 * @param int $orderId Order ID
	 */
	public function __construct(
		protected int $orderId,
	) {
	}
}
