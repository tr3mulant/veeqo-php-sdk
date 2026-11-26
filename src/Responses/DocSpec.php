<?php

namespace TremulantTech\Veeqo\Responses;

readonly class DocSpec
{
    /**
     * @param  list<PrintOption>|null  $printOptions
     */
    public function __construct(
        public ?string $format = null,
        public ?array $printOptions = null,
        public ?Size $size = null,
    ) {}
}
