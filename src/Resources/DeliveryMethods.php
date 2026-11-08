<?php

namespace TremulantTech\Veeqo\Resources;

use Generator;
use TremulantTech\Veeqo\Data\DeliveryMethods\CreateDeliveryMethodData;
use TremulantTech\Veeqo\Data\DeliveryMethods\UpdateDeliveryMethodData;
use TremulantTech\Veeqo\Requests\DeliveryMethods\CreateDeliveryMethod;
use TremulantTech\Veeqo\Requests\DeliveryMethods\DeleteDeliveryMethod;
use TremulantTech\Veeqo\Requests\DeliveryMethods\GetDeliveryMethod;
use TremulantTech\Veeqo\Requests\DeliveryMethods\ListDeliveryMethods;
use TremulantTech\Veeqo\Requests\DeliveryMethods\UpdateDeliveryMethod;
use TremulantTech\Veeqo\Responses\DeliveryMethod;
use TremulantTech\Veeqo\Veeqo;

class DeliveryMethods
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @return Generator<int, DeliveryMethod>
     */
    public function list(): Generator
    {
        return $this->veeqo->hydratePages(DeliveryMethod::class, new ListDeliveryMethods);
    }

    public function get(int $id): DeliveryMethod
    {
        return $this->veeqo->hydrate(DeliveryMethod::class, $this->veeqo->send(new GetDeliveryMethod($id)));
    }

    public function create(CreateDeliveryMethodData $data): DeliveryMethod
    {
        return $this->veeqo->hydrate(DeliveryMethod::class, $this->veeqo->send(new CreateDeliveryMethod($data)));
    }

    public function update(int $id, UpdateDeliveryMethodData $data): DeliveryMethod
    {
        return $this->veeqo->hydrate(DeliveryMethod::class, $this->veeqo->send(new UpdateDeliveryMethod($id, $data)));
    }

    public function delete(int $id): void
    {
        $this->veeqo->send(new DeleteDeliveryMethod($id));
    }
}
