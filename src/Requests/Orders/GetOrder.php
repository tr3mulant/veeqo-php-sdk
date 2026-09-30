<?php

namespace IronGate\Veeqo\Requests\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-an-order-detail/
 */
class GetOrder extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->id}";
    }
}
