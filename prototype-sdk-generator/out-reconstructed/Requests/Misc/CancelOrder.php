<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Cancel an Order
 */
class CancelOrder extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/orders/{$this->orderId}/cancel";
	}


	/**
	 * @param int $orderId Order ID
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected int $orderId,
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
