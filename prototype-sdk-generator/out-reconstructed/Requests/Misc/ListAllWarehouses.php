<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Warehouses
 *
 * Retrieve a list of available warehouses.
 */
class ListAllWarehouses extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/warehouses";
	}


	/**
	 * @param null|int $pageSize Amount of results
	 * @param null|int $page Page to show
	 * @param null|string $externalId Show only warehouses with this external ID.
	 */
	public function __construct(
		protected ?int $pageSize = null,
		protected ?int $page = null,
		protected ?string $externalId = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['page_size' => $this->pageSize, 'page' => $this->page, 'external_id' => $this->externalId]);
	}
}
