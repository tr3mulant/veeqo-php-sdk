<?php

namespace TremulantTech\Veeqo\Data\DeliveryMethods;

readonly class UpdateDeliveryMethodData
{
    /**
     * @param  numeric-string|null  $cost
     */
    public function __construct(
        public ?string $name = null,
        public ?string $cost = null,
    ) {}
}
