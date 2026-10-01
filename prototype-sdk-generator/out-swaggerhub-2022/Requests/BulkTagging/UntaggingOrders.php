<?php

namespace IronGate\Veeqo\Requests\BulkTagging;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Untagging Orders
 */
class UntaggingOrders extends Request
{
	protected Method $method = Method::DELETE;


	public function resolveEndpoint(): string
	{
		return "/bulk_tagging";
	}


	public function __construct()
	{
	}
}
