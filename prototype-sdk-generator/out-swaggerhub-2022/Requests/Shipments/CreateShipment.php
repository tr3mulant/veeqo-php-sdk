<?php

namespace IronGate\Veeqo\Requests\Shipments;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Shipment
 */
class CreateShipment extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/shipments";
	}


	public function __construct()
	{
	}
}
