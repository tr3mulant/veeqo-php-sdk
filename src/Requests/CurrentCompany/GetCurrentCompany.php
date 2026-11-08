<?php

namespace TremulantTech\Veeqo\Requests\CurrentCompany;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-company-detail/
 */
class GetCurrentCompany extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/current_company';
    }
}
