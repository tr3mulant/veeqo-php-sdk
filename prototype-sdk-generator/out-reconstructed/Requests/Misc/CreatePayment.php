<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Payment
 *
 * Creates a payment for an order. This can be used to mark an order as paid so it moves out of the
 * ‘Awaiting payment’ state.
 */
class CreatePayment extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/payments";
	}


	public function __construct()
	{
	}
}
