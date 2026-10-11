<?php

namespace TremulantTech\Veeqo\Data\Orders;

final readonly class LineItemData
{
    /**
     * @param  numeric-string  $pricePerUnit
     * @param  numeric-string|null  $taxlessDiscountPerUnit
     */
    public function __construct(
        public int $sellableId,
        public string $pricePerUnit,
        public int $quantity,
        public ?float $taxRate = null,
        public ?string $taxlessDiscountPerUnit = null,
        public ?string $additionalOptions = null,
        public ?string $remoteId = null,
    ) {}
}
