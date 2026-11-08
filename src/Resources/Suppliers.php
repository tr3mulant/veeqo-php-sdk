<?php

namespace TremulantTech\Veeqo\Resources;

use Generator;
use TremulantTech\Veeqo\Data\Suppliers\CreateSupplierData;
use TremulantTech\Veeqo\Data\Suppliers\UpdateSupplierData;
use TremulantTech\Veeqo\Requests\Suppliers\CreateSupplier;
use TremulantTech\Veeqo\Requests\Suppliers\DeleteSupplier;
use TremulantTech\Veeqo\Requests\Suppliers\GetSupplier;
use TremulantTech\Veeqo\Requests\Suppliers\ListSuppliers;
use TremulantTech\Veeqo\Requests\Suppliers\UpdateSupplier;
use TremulantTech\Veeqo\Responses\Supplier;
use TremulantTech\Veeqo\Veeqo;

class Suppliers
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @return Generator<int, Supplier>
     */
    public function list(): Generator
    {
        return $this->veeqo->hydratePages(Supplier::class, new ListSuppliers);
    }

    public function get(int $id): Supplier
    {
        return $this->veeqo->hydrate(Supplier::class, $this->veeqo->send(new GetSupplier($id)));
    }

    public function create(CreateSupplierData $data): Supplier
    {
        return $this->veeqo->hydrate(Supplier::class, $this->veeqo->send(new CreateSupplier($data)));
    }

    public function update(int $id, UpdateSupplierData $data): Supplier
    {
        return $this->veeqo->hydrate(Supplier::class, $this->veeqo->send(new UpdateSupplier($id, $data)));
    }

    public function delete(int $id): void
    {
        $this->veeqo->send(new DeleteSupplier($id));
    }
}
