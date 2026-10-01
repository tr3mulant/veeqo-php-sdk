<?php

namespace IronGate\Veeqo\Requests\Misc;

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
	 * @param null|int $sinceId Show only products with an ID greater than this number.
	 * @param null|int $warehouseId Restrict results to products with stock in specific location.
	 * @param null|string $createdAtMin Show entities created after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param null|string $updatedAtMin Show entities updated after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param null|int $pageSize Number of results per page. Maximum 100.
	 * @param null|int $page The page to show.
	 * @param null|string $query Search for products by name or SKU code.
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
