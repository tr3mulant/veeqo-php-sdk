<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\Tags\CreateTagData;
use TremulantTech\Veeqo\Requests\Tags\CreateTag;
use TremulantTech\Veeqo\Requests\Tags\DeleteTag;
use TremulantTech\Veeqo\Requests\Tags\GetTag;
use TremulantTech\Veeqo\Requests\Tags\ListTags;
use TremulantTech\Veeqo\Responses\Tag;
use TremulantTech\Veeqo\Veeqo;

class Tags
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * Not paginated: GET /tags documents no paging, and Veeqo returns every tag at once.
     *
     * @return list<Tag>
     */
    public function list(): array
    {
        return $this->veeqo->hydrateList(Tag::class, $this->veeqo->send(new ListTags));
    }

    public function get(int $id): Tag
    {
        return $this->veeqo->hydrate(Tag::class, $this->veeqo->send(new GetTag($id)));
    }

    public function create(CreateTagData $data): Tag
    {
        return $this->veeqo->hydrate(Tag::class, $this->veeqo->send(new CreateTag($data)));
    }

    public function delete(int $id): void
    {
        $this->veeqo->send(new DeleteTag($id));
    }
}
