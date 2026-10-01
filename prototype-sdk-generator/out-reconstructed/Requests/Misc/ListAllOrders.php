<?php

namespace IronGate\Veeqo\Requests\Misc;

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
	 * @param null|array $channelIds Only show orders from the given channel ID(s). Accepts a single ID or an array. For multiple IDs, use bracket array form, for example channel_ids[]=12345&channel_ids[]=67890 .
	 * @param null|string $createdafter Only show orders created on or after this date.
	 * @param null|string $createdbefore Only show orders created on or before this date (the whole day is included). Combine with created[after] to bound a date range server-side instead of scanning from created_at_min onwards.
	 * @param null|string $dueafter Only show orders due on or after this date.
	 * @param null|string $duebefore Only show orders due on or before this date (the whole day is included).
	 * @param null|int $pageSize Amount of results
	 * @param null|int $page Page to show
	 * @param null|string $query Free text search
	 * @param null|string $status Order Status
	 * @param null|string $tags Restrict results to orders with a tag of the name provided
	 * @param null|int $allocatedAt Restrict results to orders allocated at a specific warehouse
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected ?int $sinceId = null,
		protected ?string $createdAtMin = null,
		protected ?string $updatedAtMin = null,
		protected ?array $channelIds = null,
		protected ?string $createdafter = null,
		protected ?string $createdbefore = null,
		protected ?string $dueafter = null,
		protected ?string $duebefore = null,
		protected ?int $pageSize = null,
		protected ?int $page = null,
		protected ?string $query = null,
		protected ?string $status = null,
		protected ?string $tags = null,
		protected ?int $allocatedAt = null,
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter([
			'since_id' => $this->sinceId,
			'created_at_min' => $this->createdAtMin,
			'updated_at_min' => $this->updatedAtMin,
			'channel_ids' => $this->channelIds,
			'created[after]' => $this->createdafter,
			'created[before]' => $this->createdbefore,
			'due[after]' => $this->dueafter,
			'due[before]' => $this->duebefore,
			'page_size' => $this->pageSize,
			'page' => $this->page,
			'query' => $this->query,
			'status' => $this->status,
			'tags' => $this->tags,
			'allocated_at' => $this->allocatedAt,
		]);
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
