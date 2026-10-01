<?php

namespace IronGate\Veeqo\Requests\Orders;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Orders
 */
class ListAllOrders extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/orders";
	}


	/**
	 * @param null|int $sinceId Restrict results to after specified ID
	 * @param null|string $createdAtMin Show entities created after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param null|string $updatedAtMin Show entities updated after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param null|int $pageSize Amount of results
	 * @param null|int $page Page to show
	 * @param null|string $query Free text search
	 * @param null|string $status Order Status
	 * @param null|string $tags Restrict results to orders with a tag of the name provided
	 * @param null|int $allocatedAt Restrict results to orders allocated at a specific warehouse
	 */
	public function __construct(
		protected ?int $sinceId = null,
		protected ?string $createdAtMin = null,
		protected ?string $updatedAtMin = null,
		protected ?int $pageSize = null,
		protected ?int $page = null,
		protected ?string $query = null,
		protected ?string $status = null,
		protected ?string $tags = null,
		protected ?int $allocatedAt = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter([
			'since_id' => $this->sinceId,
			'created_at_min' => $this->createdAtMin,
			'updated_at_min' => $this->updatedAtMin,
			'page_size' => $this->pageSize,
			'page' => $this->page,
			'query' => $this->query,
			'status' => $this->status,
			'tags' => $this->tags,
			'allocated_at' => $this->allocatedAt,
		]);
	}
}
