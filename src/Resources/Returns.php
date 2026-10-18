<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Requests\Returns\ListReturns;
use TremulantTech\Veeqo\Responses\OrderReturn;
use TremulantTech\Veeqo\Veeqo;

class Returns
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * Not paginated: Veeqo returns every return on the order at once.
     *
     * @return list<OrderReturn>
     */
    public function list(int $orderId): array
    {
        return $this->veeqo->hydrateList(OrderReturn::class, $this->veeqo->send(new ListReturns($orderId)));
    }
}
