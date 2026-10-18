<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Image
{
    public function __construct(
        public int $id,
        public ?DateTimeImmutable $createdAt = null,
        public ?int $displayPosition = null,
        public ?string $pictureContentType = null,
        public ?string $pictureFileName = null,
        public ?int $pictureFileSize = null,
        public ?int $pictureOrder = null,
        public ?DateTimeImmutable $pictureUpdatedAt = null,
        public ?int $productId = null,
        public ?string $src = null,
        public ?DateTimeImmutable $updatedAt = null,
    ) {}
}
