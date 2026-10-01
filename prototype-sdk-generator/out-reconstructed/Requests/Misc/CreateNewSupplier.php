<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a New Supplier
 *
 * 🚀 Paid plan only
 */
class CreateNewSupplier extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/suppliers";
	}


	public function __construct()
	{
	}
}
