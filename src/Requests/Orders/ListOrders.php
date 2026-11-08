<?php

namespace TremulantTech\Veeqo\Requests\Orders;

use DateTimeInterface;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use TremulantTech\Veeqo\Enums\OrderStatus;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-orders/
 */
class ListOrders extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /** @var array<string, int|string|list<int>> */
    private readonly array $filters;

    /**
     * @param  list<int>|null  $channelIds
     * @param  DateTimeInterface|null  $createdBefore  The whole UTC day of this date is included.
     * @param  DateTimeInterface|null  $dueBefore  The whole UTC day of this date is included.
     */
    public function __construct(
        ?int $sinceId = null,
        ?DateTimeInterface $createdAtMin = null,
        ?DateTimeInterface $updatedAtMin = null,
        ?array $channelIds = null,
        ?DateTimeInterface $createdAfter = null,
        ?DateTimeInterface $createdBefore = null,
        ?DateTimeInterface $dueAfter = null,
        ?DateTimeInterface $dueBefore = null,
        ?string $query = null,
        ?OrderStatus $status = null,
        ?string $tags = null,
        ?int $allocatedAt = null,
    ) {
        $this->filters = array_filter([
            'since_id' => $sinceId,
            'created_at_min' => Veeqo::queryDate($createdAtMin),
            'updated_at_min' => Veeqo::queryDate($updatedAtMin),
            'status' => $status?->value,
            'channel_ids' => $channelIds,
            'created[after]' => Veeqo::queryDate($createdAfter),
            'created[before]' => Veeqo::queryDate($createdBefore),
            'due[after]' => Veeqo::queryDate($dueAfter),
            'due[before]' => Veeqo::queryDate($dueBefore),
            'query' => $query,
            'tags' => $tags,
            'allocated_at' => $allocatedAt,
        ], fn (mixed $value) => $value !== null);
    }

    public function resolveEndpoint(): string
    {
        return '/orders';
    }

    protected function defaultQuery(): array
    {
        return $this->filters;
    }
}
