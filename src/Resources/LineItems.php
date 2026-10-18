<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\LineItems\UpdateLineItemData;
use TremulantTech\Veeqo\Requests\LineItems\UpdateLineItem;
use TremulantTech\Veeqo\Veeqo;

class LineItems
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function update(int $id, UpdateLineItemData $data): void
    {
        $this->veeqo->send(new UpdateLineItem($id, $data));
    }
}
