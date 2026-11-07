<?php

namespace TremulantTech\Veeqo\Requests\CurrentCompany;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\CurrentCompany\UpdateCurrentCompanyData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-company-detail/
 */
class UpdateCurrentCompany extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected UpdateCurrentCompanyData $data) {}

    public function resolveEndpoint(): string
    {
        return '/current_company';
    }

    /**
     * Unenveloped, as the spec documents.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return Veeqo::normalize($this->data);
    }
}
