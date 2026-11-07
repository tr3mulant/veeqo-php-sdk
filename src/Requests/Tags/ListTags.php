<?php

namespace TremulantTech\Veeqo\Requests\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-tags/
 */
class ListTags extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/tags';
    }
}
