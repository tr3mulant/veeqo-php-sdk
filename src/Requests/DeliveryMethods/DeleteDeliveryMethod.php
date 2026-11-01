<?php

namespace TremulantTech\Veeqo\Requests\DeliveryMethods;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-delivery-method/
 */
class DeleteDeliveryMethod extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/delivery_methods/{$this->id}";
    }
}
