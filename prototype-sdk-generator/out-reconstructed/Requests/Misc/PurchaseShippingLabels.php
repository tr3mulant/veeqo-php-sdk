<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Purchase Shipping Labels
 *
 * Purchase a shipping label for an order’s allocation. Ensure you fetch rates first via Retrieve
 * Shipping Rates , then copy your chosen quote’s fields into the matching shipment field.
 */
class PurchaseShippingLabels extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/shipping/shipments";
	}


	public function __construct()
	{
	}
}
