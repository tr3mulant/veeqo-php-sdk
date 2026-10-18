<?php

namespace TremulantTech\Veeqo\Resources;

use DateTimeInterface;
use Generator;
use LogicException;
use Saloon\Http\Response;
use TremulantTech\Veeqo\Data\Orders\CancelOrderData;
use TremulantTech\Veeqo\Data\Orders\CreateOrderData;
use TremulantTech\Veeqo\Data\Orders\UpdateOrderData;
use TremulantTech\Veeqo\Enums\OrderStatus;
use TremulantTech\Veeqo\Requests\Orders\CancelOrder;
use TremulantTech\Veeqo\Requests\Orders\CreateOrder;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Requests\Orders\ListOrders;
use TremulantTech\Veeqo\Requests\Orders\UpdateOrder;
use TremulantTech\Veeqo\Responses\Order;
use TremulantTech\Veeqo\Veeqo;

class Orders
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @param  list<int>|null  $channelIds
     * @param  DateTimeInterface|null  $createdBefore  The whole UTC day of this date is included.
     * @param  DateTimeInterface|null  $dueBefore  The whole UTC day of this date is included.
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
        ?OrderStatus $status = null,
        ?string $tags = null,
        ?int $allocatedAt = null,
    ): Generator {
        $request = new ListOrders($sinceId, $createdAtMin, $updatedAtMin, $channelIds, $createdAfter, $createdBefore, $dueAfter, $dueBefore, $query, $status, $tags, $allocatedAt);

        foreach ($this->veeqo->paginate($request) as $page) {
            if (! $page instanceof Response) {
                throw new LogicException('Expected a synchronous page');
            }
            foreach ($this->veeqo->hydrateList(Order::class, $page) as $order) {
                yield $order;
            }
        }
    }

    public function get(int $id): Order
    {
        return $this->veeqo->hydrate(Order::class, $this->veeqo->send(new GetOrder($id)));
    }

    public function create(CreateOrderData $data): Order
    {
        return $this->veeqo->hydrate(Order::class, $this->veeqo->send(new CreateOrder($data)));
    }

    public function update(int $id, UpdateOrderData $data): void
    {
        $this->veeqo->send(new UpdateOrder($id, $data));
    }

    public function cancel(int $id, ?CancelOrderData $data = null): void
    {
        $this->veeqo->send(new CancelOrder($id, $data));
    }
}
