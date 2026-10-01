<?php

namespace IronGate\Veeqo\Requests\Products;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Products
 */
class ListAllProducts extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/products";
	}


	/**
	 * @param null|int $sinceId Restrict results to after specified ID
	 * @param null|int $warehouseId Restrict results to products with stock in specific warehouse
	 * @param null|string $createdAtMin Show entities created after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param null|string $updatedAtMin Show entities updated after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param null|int $pageSize Amount of results per page
	 * @param null|int $page Page to show
	 * @param null|string $query Free text search
	 */
	public function __construct(
		protected ?int $sinceId = null,
		protected ?int $warehouseId = null,
		protected ?string $createdAtMin = null,
		protected ?string $updatedAtMin = null,
		protected ?int $pageSize = null,
		protected ?int $page = null,
		protected ?string $query = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter([
			'since_id' => $this->sinceId,
			'warehouse_id' => $this->warehouseId,
			'created_at_min' => $this->createdAtMin,
			'updated_at_min' => $this->updatedAtMin,
			'page_size' => $this->pageSize,
			'page' => $this->page,
			'query' => $this->query,
		]);
	}
}
