<?php

namespace IronGate\Veeqo\Requests\Warehouses;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Warehouses
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
