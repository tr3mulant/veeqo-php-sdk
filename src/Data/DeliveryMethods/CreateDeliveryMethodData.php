<?php

namespace TremulantTech\Veeqo\Data\DeliveryMethods;

readonly class CreateDeliveryMethodData
{
    /**
     * @param  numeric-string|null  $cost
     */
    public function __construct(
        public string $name,
        public ?string $cost = null,
    ) {}
}
