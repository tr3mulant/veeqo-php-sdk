<?php

namespace TremulantTech\Veeqo\Requests\DeliveryMethods;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-delivery-methods/
 */
class ListDeliveryMethods extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/delivery_methods';
    }
}
