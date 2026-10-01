<?php

namespace IronGate\Veeqo\Requests\Company;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Company Detail
 */
class UpdateCompanyDetail extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/current_company";
	}


	public function __construct()
	{
	}
}
