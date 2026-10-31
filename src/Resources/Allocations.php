<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\Allocations\CreateAllocationData;
use TremulantTech\Veeqo\Data\Allocations\UpdateAllocationData;
use TremulantTech\Veeqo\Requests\Allocations\CreateAllocation;
use TremulantTech\Veeqo\Requests\Allocations\DeleteAllocation;
use TremulantTech\Veeqo\Requests\Allocations\UpdateAllocation;
use TremulantTech\Veeqo\Responses\Allocation;
use TremulantTech\Veeqo\Responses\Order;
use TremulantTech\Veeqo\Veeqo;

class Allocations
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * Veeqo answers with the whole order, its new allocation among `allocations`.
     */
    public function create(int $orderId, CreateAllocationData $data): Order
    {
        return $this->veeqo->hydrate(Order::class, $this->veeqo->send(new CreateAllocation($orderId, $data)));
    }

    public function update(int $orderId, int $id, UpdateAllocationData $data): Allocation
    {
        return $this->veeqo->hydrate(Allocation::class, $this->veeqo->send(new UpdateAllocation($orderId, $id, $data)));
    }

    public function delete(int $orderId, int $id): void
    {
        $this->veeqo->send(new DeleteAllocation($orderId, $id));
    }
}
