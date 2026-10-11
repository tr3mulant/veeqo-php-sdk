<?php

namespace TremulantTech\Veeqo\Data;

final readonly class DeliveryMethod
{
    /**
     * @param numeric-string $cost
     */
    public function __construct(
        public int $id,
        public string $cost,
        public string $name,
    ) {}
}
