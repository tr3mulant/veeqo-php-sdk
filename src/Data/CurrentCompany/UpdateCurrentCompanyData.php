<?php

namespace TremulantTech\Veeqo\Data\CurrentCompany;

readonly class UpdateCurrentCompanyData
{
    public function __construct(
        public ?string $name = null,
    ) {}
}
