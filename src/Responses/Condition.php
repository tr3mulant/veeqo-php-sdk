<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Condition
{
    public function __construct(
        public int $limit,
        public int $usage,
    ) {}
}
