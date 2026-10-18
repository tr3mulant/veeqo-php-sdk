<?php

namespace TremulantTech\Veeqo\Requests\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Orders\CancelOrderData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/cancel-an-order/
 */
class CancelOrder extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $id, protected ?CancelOrderData $data = null) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->id}/cancel";
    }

    /**
     * Unenveloped, and carrying the order id the path already holds, as the spec documents.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['id' => $this->id, ...($this->data === null ? [] : Veeqo::normalize($this->data))];
    }
}
