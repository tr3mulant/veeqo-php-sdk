<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\Shipping\PurchaseLabelsData;
use TremulantTech\Veeqo\Requests\Shipping\PurchaseLabels;
use TremulantTech\Veeqo\Responses\Shipment;
use TremulantTech\Veeqo\Veeqo;

class Shipping
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * Buys a label, so a 5xx or connection failure is never retried (ADR 0001): check the allocation before sending again.
     */
    public function purchaseLabels(PurchaseLabelsData $data): Shipment
    {
        return $this->veeqo->hydrate(Shipment::class, $this->veeqo->send(new PurchaseLabels($data)));
    }
}
