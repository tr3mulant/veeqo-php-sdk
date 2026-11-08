<?php

namespace TremulantTech\Veeqo\Responses;

readonly class SupportedDocumentDetail
{
    public function __construct(
        public ?bool $isMandatory = null,
        public ?string $name = null,
    ) {}
}
