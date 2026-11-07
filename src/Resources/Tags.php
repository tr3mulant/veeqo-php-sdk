<?php

namespace TremulantTech\Veeqo\Resources;

use Generator;
use LogicException;
use Saloon\Http\Response;
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
     * @return Generator<int, Tag>
     */
    public function list(): Generator
    {
        foreach ($this->veeqo->paginate(new ListTags) as $page) {
            if (! $page instanceof Response) {
                throw new LogicException('Expected a synchronous page');
            }
            foreach ($this->veeqo->hydrateList(Tag::class, $page) as $tag) {
                yield $tag;
            }
        }
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
