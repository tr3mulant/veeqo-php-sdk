<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Tag
{
    public function __construct(
        public int $id,
        public string $name,
        public string $colour,
        public int $companyId,
        public int $taggingsCount,
    ) {}
}
