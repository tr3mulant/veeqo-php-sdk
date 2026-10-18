<?php

namespace TremulantTech\Veeqo\Data\Notes;

readonly class CreateNoteData
{
    public function __construct(
        public string $text,
    ) {}
}
