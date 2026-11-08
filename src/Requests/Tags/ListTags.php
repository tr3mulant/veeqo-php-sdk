<?php

namespace TremulantTech\Veeqo\Requests\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-tags/
 */
class ListTags extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/tags';
    }
}
