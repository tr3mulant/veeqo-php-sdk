<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ProductInventory
{
    public function __construct(
        public bool $infinite,
    ) {}
}
