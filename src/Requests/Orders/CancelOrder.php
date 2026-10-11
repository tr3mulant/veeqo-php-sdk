<?php

namespace TremulantTech\Veeqo\Requests\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Orders\CancelOrderData;
use TremulantTech\Veeqo\Valinor;

/**
 * @see https://developers.veeqo.com/api/operations/cancel-an-order/
 */
class CancelOrder extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $orderId, protected CancelOrderData $data = new CancelOrderData) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/cancel";
    }

    /** @return array<mixed> */
    protected function defaultBody(): array
    {
        return Valinor::body($this->data);
    }
}
