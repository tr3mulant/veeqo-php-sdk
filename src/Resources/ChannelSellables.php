<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\ChannelSellables\CreateChannelSellableData;
use TremulantTech\Veeqo\Requests\ChannelSellables\CreateChannelSellable;
use TremulantTech\Veeqo\Responses\ChannelSellable;
use TremulantTech\Veeqo\Veeqo;

class ChannelSellables
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function create(CreateChannelSellableData $data): ChannelSellable
    {
        return $this->veeqo->hydrateJsonApi(ChannelSellable::class, $this->veeqo->send(new CreateChannelSellable($data)));
    }
}
