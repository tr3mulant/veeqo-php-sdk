<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Shipment
 *
 * Creates a shipment against an order’s allocation, marking the order as shipped. Use this to record
 * a fulfilment in Veeqo — including orders shipped outside of Veeqo — without purchasing a label.
 * A carrier_id is required. For orders fulfilled externally, or with a carrier not integrated with
 * Veeqo, use the built-in “Other” carrier ( carrier_id: 3 ). The full list of built-in carrier IDs
 * is in the Shipments overview .
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
