<?php

namespace TremulantTech\Veeqo\Requests\DeliveryMethods;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-delivery-method/
 */
class GetDeliveryMethod extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/delivery_methods/{$this->id}";
    }
}
