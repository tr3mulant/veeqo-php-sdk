<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Show Returns on Order
 */
class ShowReturnsOnOrder extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/orders/{$this->id}/returns";
	}


	/**
	 * @param float|int $sellableId Sellable ID
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected float|int $sellableId,
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['sellable_id' => $this->sellableId]);
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
