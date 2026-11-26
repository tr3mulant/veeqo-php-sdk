<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class TrackingEvent
{
    public function __construct(
        public ?DateTimeImmutable $timestamp = null,
        public ?string $description = null,
        public ?string $location = null,
        public ?string $status = null,
    ) {}
}
