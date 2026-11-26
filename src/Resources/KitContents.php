<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\KitContents\CreateKitContentData;
use TremulantTech\Veeqo\Data\KitContents\UpdateKitContentData;
use TremulantTech\Veeqo\Requests\KitContents\CreateKitContent;
use TremulantTech\Veeqo\Requests\KitContents\DeleteKitContent;
use TremulantTech\Veeqo\Requests\KitContents\GetKitContent;
use TremulantTech\Veeqo\Requests\KitContents\UpdateKitContent;
use TremulantTech\Veeqo\Responses\KitContent;
use TremulantTech\Veeqo\Veeqo;

class KitContents
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function get(int $kitId, int $id): KitContent
    {
        return $this->veeqo->hydrate(KitContent::class, $this->veeqo->send(new GetKitContent($kitId, $id)));
    }

    public function create(int $kitId, CreateKitContentData $data): KitContent
    {
        return $this->veeqo->hydrate(KitContent::class, $this->veeqo->send(new CreateKitContent($kitId, $data)));
    }

    public function update(int $kitId, int $id, UpdateKitContentData $data): KitContent
    {
        return $this->veeqo->hydrate(KitContent::class, $this->veeqo->send(new UpdateKitContent($kitId, $id, $data)));
    }

    public function delete(int $kitId, int $id): void
    {
        $this->veeqo->send(new DeleteKitContent($kitId, $id));
    }
}
