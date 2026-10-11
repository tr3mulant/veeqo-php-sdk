<?php

namespace TremulantTech\Veeqo\Data\Orders;

final readonly class CustomerNoteData
{
    public function __construct(
        public string $text,
    ) {}
}
