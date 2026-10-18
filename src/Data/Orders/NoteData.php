<?php

namespace TremulantTech\Veeqo\Data\Orders;

readonly class NoteData
{
    public function __construct(
        public ?string $text = null,
    ) {}
}
