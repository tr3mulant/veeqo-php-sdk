<?php

namespace TremulantTech\Veeqo\Responses;

/**
 * The stub Veeqo documents as the answer to an update: the full shape minus `id` and `product_property_id`.
 */
readonly class ProductPropertySpecificSummary
{
    public function __construct(
        public ?string $productPropertyName = null,
        public ?string $value = null,
    ) {}
}
