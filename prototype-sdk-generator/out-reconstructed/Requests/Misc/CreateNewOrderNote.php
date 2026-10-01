<?php

namespace IronGate\Veeqo\Requests\Misc;

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
