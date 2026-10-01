<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Tags
 */
class ListAllTags extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/tags";
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
