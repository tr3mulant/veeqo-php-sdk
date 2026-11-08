<?php

namespace TremulantTech\Veeqo\Data\Kits;

readonly class CreateKitData
{
    /**
     * @param  list<KitContentData>|null  $contents
     */
    public function __construct(
        public ?int $productVariantId = null,
        public ?array $contents = null,
    ) {}
}
