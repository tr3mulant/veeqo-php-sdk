<?php

namespace TremulantTech\Veeqo\Resources;

use Generator;
use TremulantTech\Veeqo\Data\Channels\CreateChannelData;
use TremulantTech\Veeqo\Data\Channels\UpdateChannelData;
use TremulantTech\Veeqo\Requests\Channels\CreateChannel;
use TremulantTech\Veeqo\Requests\Channels\DeleteChannel;
use TremulantTech\Veeqo\Requests\Channels\GetChannel;
use TremulantTech\Veeqo\Requests\Channels\ListChannels;
use TremulantTech\Veeqo\Requests\Channels\UpdateChannel;
use TremulantTech\Veeqo\Responses\Channel;
use TremulantTech\Veeqo\Veeqo;

class Channels
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @return Generator<int, Channel>
     */
    public function list(): Generator
    {
        return $this->veeqo->hydratePages(Channel::class, new ListChannels);
    }

    public function get(int $id): Channel
    {
        return $this->veeqo->hydrate(Channel::class, $this->veeqo->send(new GetChannel($id)));
    }

    public function create(CreateChannelData $data): Channel
    {
        return $this->veeqo->hydrate(Channel::class, $this->veeqo->send(new CreateChannel($data)));
    }

    /**
     * Veeqo answers 204, so nothing is returned (ADR 0004).
     */
    public function update(int $id, UpdateChannelData $data): void
    {
        $this->veeqo->send(new UpdateChannel($id, $data));
    }

    public function delete(int $id): void
    {
        $this->veeqo->send(new DeleteChannel($id));
    }
}
