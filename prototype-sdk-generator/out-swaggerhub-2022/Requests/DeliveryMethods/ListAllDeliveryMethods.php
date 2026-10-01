<?php

namespace IronGate\Veeqo\Requests\DeliveryMethods;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Delivery Methods
 */
class ListAllDeliveryMethods extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/delivery_methods";
	}


	/**
	 * @param null|int $pageSize Amount of results
	 * @param null|int $page Page to show
	 */
	public function __construct(
		protected ?int $pageSize = null,
		protected ?int $page = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['page_size' => $this->pageSize, 'page' => $this->page]);
	}
}
