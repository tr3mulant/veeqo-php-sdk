<?php

namespace TremulantTech\Veeqo\Resources;

use DateTimeInterface;
use Generator;
use Saloon\Http\Response;
use TremulantTech\Veeqo\Data\Order;
use TremulantTech\Veeqo\Data\Orders\CancelOrderData;
use TremulantTech\Veeqo\Data\Orders\CreateOrderData;
use TremulantTech\Veeqo\Data\Orders\UpdateOrderData;
use TremulantTech\Veeqo\Requests\Orders\CancelOrder;
use TremulantTech\Veeqo\Requests\Orders\CreateOrder;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Requests\Orders\ListOrders;
use TremulantTech\Veeqo\Requests\Orders\UpdateOrder;
use TremulantTech\Veeqo\Veeqo;

final readonly class Orders
{
    public function __construct(private Veeqo $connector) {}

    /**
     * Every matching order, one request per 100 as you iterate.
     *
     * @param  list<int>|null  $channelIds
     * @return Generator<int, Order>
     */
    public function list(
        ?int $sinceId = null,
        ?DateTimeInterface $createdAtMin = null,
        ?DateTimeInterface $updatedAtMin = null,
        ?array $channelIds = null,
        ?DateTimeInterface $createdAfter = null,
        ?DateTimeInterface $createdBefore = null,
        ?DateTimeInterface $dueAfter = null,
        ?DateTimeInterface $dueBefore = null,
        ?string $query = null,
        ?string $status = null,
        ?string $tags = null,
        ?int $allocatedAt = null,
    ): Generator {
        $request = new ListOrders($sinceId, $createdAtMin, $updatedAtMin, $channelIds, $createdAfter, $createdBefore, $dueAfter, $dueBefore, $query, $status, $tags, $allocatedAt);

        foreach ($this->connector->paginate($request) as $response) {
            if (! $response instanceof Response) {
                continue;
            }
            // Not `yield from`: each page's keys restart at 0, which would collide in iterator_to_array().
            foreach ($request->createDtoFromResponse($response) as $order) {
                yield $order;
            }
        }
    }

    public function get(int $orderId): Order
    {
        $request = new GetOrder($orderId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function create(CreateOrderData $data): Order
    {
        $request = new CreateOrder($data);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function update(int $orderId, UpdateOrderData $data): void
    {
        $this->connector->send(new UpdateOrder($orderId, $data));
    }

    public function cancel(int $orderId, CancelOrderData $data = new CancelOrderData): void
    {
        $this->connector->send(new CancelOrder($orderId, $data));
    }
}
