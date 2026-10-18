<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Features
{
    public function __construct(
        public ?string $orders = null,
        public ?bool $productPusher = null,
        public ?string $products = null,
        public ?bool $stockTake = null,
        public ?string $stores = null,
        public ?string $support = null,
        public ?string $users = null,
    ) {}
}
