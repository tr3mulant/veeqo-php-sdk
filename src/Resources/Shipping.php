<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\Shipping\PurchaseLabelsData;
use TremulantTech\Veeqo\Requests\Shipping\PurchaseLabels;
use TremulantTech\Veeqo\Responses\ShipmentSummary;
use TremulantTech\Veeqo\Veeqo;

class Shipping
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * Buys a label, so a 5xx or connection failure is never retried (ADR 0001): check the allocation before sending again.
     */
    public function purchaseLabels(PurchaseLabelsData $data): ShipmentSummary
    {
        return $this->veeqo->hydrate(ShipmentSummary::class, $this->veeqo->send(new PurchaseLabels($data)));
    }
}
