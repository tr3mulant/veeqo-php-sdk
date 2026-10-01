<?php

namespace IronGate\Veeqo\Requests\Company;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View Company Detail
 */
class ViewCompanyDetail extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/current_company";
	}


	public function __construct()
	{
	}
}
