<?php

namespace IronGate\Veeqo\Requests\Warehouses;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Warehouse
 */
class CreateWarehouse extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/warehouses";
	}


	public function __construct()
	{
	}
}
