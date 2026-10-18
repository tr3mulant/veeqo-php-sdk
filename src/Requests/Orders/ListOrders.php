<?php

namespace TremulantTech\Veeqo\Requests\Orders;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use TremulantTech\Veeqo\Enums\OrderStatus;

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
            'created_at_min' => self::utc($createdAtMin),
            'updated_at_min' => self::utc($updatedAtMin),
            'status' => $status?->value,
            'channel_ids' => $channelIds,
            'created[after]' => self::utc($createdAfter),
            'created[before]' => self::utc($createdBefore),
            'due[after]' => self::utc($dueAfter),
            'due[before]' => self::utc($dueBefore),
            'query' => $query,
            'tags' => $tags,
            'allocated_at' => $allocatedAt,
        ], fn (mixed $value) => $value !== null);
    }

    /**
     * In UTC, because Veeqo's *[before] filters include the whole UTC day of the date sent.
     */
    private static function utc(?DateTimeInterface $date): ?string
    {
        return $date === null ? null : DateTimeImmutable::createFromInterface($date)->setTimezone(new DateTimeZone('UTC'))->format(DATE_ATOM);
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
