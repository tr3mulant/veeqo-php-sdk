<?php

namespace TremulantTech\Veeqo\Requests\Returns;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/show-returns-on-order/
 */
class ListReturns extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $orderId) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/returns";
    }
}
