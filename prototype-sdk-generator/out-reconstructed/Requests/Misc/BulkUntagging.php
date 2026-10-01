<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Bulk Untagging
 *
 * Remove tags from products or orders in bulk. To untag products , use product_ids in the request
 * body. To untag orders , use order_ids in the request body instead.
 */
class BulkUntagging extends Request
{
	protected Method $method = Method::DELETE;


	public function resolveEndpoint(): string
	{
		return "/bulk_tagging";
	}


	/**
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
