<?php

namespace TremulantTech\Veeqo\Requests\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Order;
use TremulantTech\Veeqo\Data\Orders\CreateOrderData;
use TremulantTech\Veeqo\Valinor;

/**
 * @see https://developers.veeqo.com/api/operations/create-a-new-order/
 */
class CreateOrder extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateOrderData $data) {}

    public function resolveEndpoint(): string
    {
        return '/orders';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return ['order' => Valinor::body($this->data)];
    }

    public function createDtoFromResponse(Response $response): Order
    {
        return Valinor::object($response, Order::class);
    }
}
