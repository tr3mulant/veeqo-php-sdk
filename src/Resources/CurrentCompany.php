<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\CurrentCompany\UpdateCurrentCompanyData;
use TremulantTech\Veeqo\Requests\CurrentCompany\GetCurrentCompany;
use TremulantTech\Veeqo\Requests\CurrentCompany\UpdateCurrentCompany;
use TremulantTech\Veeqo\Responses\Company;
use TremulantTech\Veeqo\Veeqo;

class CurrentCompany
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function get(): Company
    {
        return $this->veeqo->hydrate(Company::class, $this->veeqo->send(new GetCurrentCompany));
    }

    /**
     * Veeqo answers 204, so nothing is returned (ADR 0004).
     */
    public function update(UpdateCurrentCompanyData $data): void
    {
        $this->veeqo->send(new UpdateCurrentCompany($data));
    }
}
