<?php

namespace IronGate\Veeqo\Requests\DeliveryMethods;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Delivery Method
 */
class CreateDeliveryMethod extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/delivery_methods";
	}


	public function __construct()
	{
	}
}
