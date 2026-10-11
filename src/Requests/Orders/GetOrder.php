<?php

namespace TremulantTech\Veeqo\Requests\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use TremulantTech\Veeqo\Data\Order;
use TremulantTech\Veeqo\Valinor;

/**
 * @see https://developers.veeqo.com/api/operations/view-an-order-detail/
 */
class GetOrder extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $orderId) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}";
    }

    public function createDtoFromResponse(Response $response): Order
    {
        return Valinor::object($response, Order::class);
    }
}
