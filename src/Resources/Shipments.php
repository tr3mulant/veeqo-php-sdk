<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\Shipments\CreateShipmentData;
use TremulantTech\Veeqo\Requests\Shipments\CreateShipment;
use TremulantTech\Veeqo\Requests\Shipments\DeleteShipment;
use TremulantTech\Veeqo\Responses\Shipment;
use TremulantTech\Veeqo\Veeqo;

class Shipments
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function create(CreateShipmentData $data): Shipment
    {
        return $this->veeqo->hydrate(Shipment::class, $this->veeqo->send(new CreateShipment($data)));
    }

    public function delete(int $id): void
    {
        $this->veeqo->send(new DeleteShipment($id));
    }
}
