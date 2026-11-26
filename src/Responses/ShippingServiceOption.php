<?php

namespace TremulantTech\Veeqo\Responses;

readonly class ShippingServiceOption
{
    /**
     * @param  list<Value>|null  $values
     */
    public function __construct(
        public ?string $key = null,
        public ?string $label = null,
        public ?string $type = null,
        public ?bool $multiple = null,
        public ?array $values = null,
        public ?Validation $validation = null,
        public ?string $unit = null,
    ) {}
}
