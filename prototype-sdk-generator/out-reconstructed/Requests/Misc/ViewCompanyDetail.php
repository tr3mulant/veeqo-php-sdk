<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View Company Detail
 *
 * Returns the current company, including its account capabilities: billing plan, active features and
 * the plan capability trees.
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
