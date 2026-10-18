<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\Notes\CreateNoteData;
use TremulantTech\Veeqo\Requests\Notes\CreateNote;
use TremulantTech\Veeqo\Responses\Note;
use TremulantTech\Veeqo\Veeqo;

class Notes
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function create(int $orderId, CreateNoteData $data): Note
    {
        return $this->veeqo->hydrate(Note::class, $this->veeqo->send(new CreateNote($orderId, $data)));
    }
}
