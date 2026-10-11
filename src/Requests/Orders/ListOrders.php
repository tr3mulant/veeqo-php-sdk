<?php

namespace TremulantTech\Veeqo\Requests\Orders;

use DateTimeInterface;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use TremulantTech\Veeqo\Data\Order;
use TremulantTech\Veeqo\Valinor;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-orders/
 */
class ListOrders extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * Veeqo silently ignores a filter it can't parse, so these are typed rather than passed as an array.
     *
     * @param  list<int>|null  $channelIds
     */
    public function __construct(
        protected ?int $sinceId = null,
        protected ?DateTimeInterface $createdAtMin = null,
        protected ?DateTimeInterface $updatedAtMin = null,
        protected ?array $channelIds = null,
        protected ?DateTimeInterface $createdAfter = null,
        protected ?DateTimeInterface $createdBefore = null,
        protected ?DateTimeInterface $dueAfter = null,
        protected ?DateTimeInterface $dueBefore = null,
        protected ?string $query = null,
        protected ?string $status = null,
        protected ?string $tags = null,
        protected ?int $allocatedAt = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/orders';
    }

    /** @return array<string, mixed> */
    protected function defaultQuery(): array
    {
        return array_filter([
            'since_id' => $this->sinceId,
            'created_at_min' => $this->createdAtMin?->format('Y-m-d H:i:s'),
            'updated_at_min' => $this->updatedAtMin?->format('Y-m-d H:i:s'),
            'channel_ids' => $this->channelIds,
            'created[after]' => $this->createdAfter?->format(DATE_ATOM),
            'created[before]' => $this->createdBefore?->format(DATE_ATOM),
            'due[after]' => $this->dueAfter?->format(DATE_ATOM),
            'due[before]' => $this->dueBefore?->format(DATE_ATOM),
            'query' => $this->query,
            'status' => $this->status,
            'tags' => $this->tags,
            'allocated_at' => $this->allocatedAt,
        ], fn (mixed $value): bool => $value !== null);
    }

    /** @return list<Order> */
    public function createDtoFromResponse(Response $response): array
    {
        return Valinor::list($response, Order::class);
    }
}
