<?php

namespace TremulantTech\Veeqo\Requests\DeliveryMethods;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\DeliveryMethods\UpdateDeliveryMethodData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-delivery-method-detail/
 */
class UpdateDeliveryMethod extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $id, protected UpdateDeliveryMethodData $data) {}

    public function resolveEndpoint(): string
    {
        return "/delivery_methods/{$this->id}";
    }

    /**
     * Unenveloped, as the spec documents.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return Veeqo::normalize($this->data);
    }
}
