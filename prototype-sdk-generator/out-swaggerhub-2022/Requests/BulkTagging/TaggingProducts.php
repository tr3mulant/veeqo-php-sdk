<?php

namespace IronGate\Veeqo\Requests\BulkTagging;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Tagging Products
 */
class TaggingProducts extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/bulk_tagging";
	}


	public function __construct()
	{
	}
}
