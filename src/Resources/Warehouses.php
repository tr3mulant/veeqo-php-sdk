<?php

namespace TremulantTech\Veeqo\Resources;

use Generator;
use TremulantTech\Veeqo\Data\Warehouses\CreateWarehouseData;
use TremulantTech\Veeqo\Data\Warehouses\UpdateWarehouseData;
use TremulantTech\Veeqo\Requests\Warehouses\CreateWarehouse;
use TremulantTech\Veeqo\Requests\Warehouses\DeleteWarehouse;
use TremulantTech\Veeqo\Requests\Warehouses\GetWarehouse;
use TremulantTech\Veeqo\Requests\Warehouses\ListWarehouses;
use TremulantTech\Veeqo\Requests\Warehouses\UpdateWarehouse;
use TremulantTech\Veeqo\Responses\Warehouse;
use TremulantTech\Veeqo\Veeqo;

class Warehouses
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @return Generator<int, Warehouse>
     */
    public function list(?string $externalId = null): Generator
    {
        return $this->veeqo->hydratePages(Warehouse::class, new ListWarehouses($externalId));
    }

    public function get(int $id): Warehouse
    {
        return $this->veeqo->hydrate(Warehouse::class, $this->veeqo->send(new GetWarehouse($id)));
    }

    public function create(CreateWarehouseData $data): Warehouse
    {
        return $this->veeqo->hydrate(Warehouse::class, $this->veeqo->send(new CreateWarehouse($data)));
    }

    /**
     * Veeqo answers 204, so nothing is returned (ADR 0004).
     */
    public function update(int $id, UpdateWarehouseData $data): void
    {
        $this->veeqo->send(new UpdateWarehouse($id, $data));
    }

    public function delete(int $id): void
    {
        $this->veeqo->send(new DeleteWarehouse($id));
    }
}
