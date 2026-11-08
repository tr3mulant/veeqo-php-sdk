<?php

namespace TremulantTech\Veeqo\Responses;

readonly class DocumentSpecification
{
    public function __construct(
        public ?DocSpec $docSpec = null,
    ) {}
}
