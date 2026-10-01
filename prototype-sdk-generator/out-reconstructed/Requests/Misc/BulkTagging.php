<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Bulk Tagging
 *
 * Add tags to products or orders in bulk. To tag products , use product_ids in the request body. To
 * tag orders , use order_ids in the request body instead.
 */
class BulkTagging extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


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
