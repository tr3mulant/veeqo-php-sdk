<?php

namespace TremulantTech\Veeqo\Requests\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Orders\UpdateOrderData;
use TremulantTech\Veeqo\Valinor;

/**
 * @see https://developers.veeqo.com/api/operations/update-order-detail/
 */
class UpdateOrder extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $orderId, protected UpdateOrderData $data) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}";
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return ['order' => Valinor::body($this->data)];
    }
}
