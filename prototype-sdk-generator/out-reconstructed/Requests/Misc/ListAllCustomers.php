<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Customers
 */
class ListAllCustomers extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/customers";
	}


	/**
	 * @param null|int $pageSize Amount of results
	 * @param null|int $page Page to show
	 * @param null|string $query Free text search
	 * @param null|string $customerType Filter by customer type
	 */
	public function __construct(
		protected ?int $pageSize = null,
		protected ?int $page = null,
		protected ?string $query = null,
		protected ?string $customerType = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['page_size' => $this->pageSize, 'page' => $this->page, 'query' => $this->query, 'customer_type' => $this->customerType]);
	}
}
