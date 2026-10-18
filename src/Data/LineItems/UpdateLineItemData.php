<?php

namespace TremulantTech\Veeqo\Data\LineItems;

readonly class UpdateLineItemData
{
    public function __construct(
        public ?string $additionalOptions = null,
    ) {}
}
