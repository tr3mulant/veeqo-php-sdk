<?php

namespace TremulantTech\Veeqo\Requests\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-stores/
 */
class ListChannels extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/channels';
    }
}
