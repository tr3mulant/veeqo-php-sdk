<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\Kits\CreateKitData;
use TremulantTech\Veeqo\Requests\Kits\CreateKit;
use TremulantTech\Veeqo\Requests\Kits\GetKit;
use TremulantTech\Veeqo\Responses\Kit;
use TremulantTech\Veeqo\Veeqo;

class Kits
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function get(int $id): Kit
    {
        return $this->veeqo->hydrate(Kit::class, $this->veeqo->send(new GetKit($id)));
    }

    public function create(CreateKitData $data): Kit
    {
        return $this->veeqo->hydrate(Kit::class, $this->veeqo->send(new CreateKit($data)));
    }
}
