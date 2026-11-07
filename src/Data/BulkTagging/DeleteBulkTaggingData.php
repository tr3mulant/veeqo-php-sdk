<?php

namespace TremulantTech\Veeqo\Data\BulkTagging;

readonly class DeleteBulkTaggingData
{
    /**
     * Send `productIds` or `orderIds`, not both.
     *
     * @param  list<int>  $tagIds
     * @param  list<int>|null  $productIds
     * @param  list<int>|null  $orderIds
     */
    public function __construct(
        public array $tagIds,
        public ?array $productIds = null,
        public ?array $orderIds = null,
    ) {}
}
