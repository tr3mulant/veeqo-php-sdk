<?php

namespace TremulantTech\Veeqo\Requests\Shipments;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Shipments\CreateShipmentData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/create-a-shipment/
 */
class CreateShipment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateShipmentData $data) {}

    public function resolveEndpoint(): string
    {
        return '/shipments';
    }

    /**
     * `allocation_id` and `order_id` sit beside the envelope, as the docs show (ADR 0010).
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $shipment = Veeqo::normalize($this->data);
        unset($shipment['allocation_id'], $shipment['order_id']);

        return ['shipment' => $shipment, 'allocation_id' => $this->data->allocationId, 'order_id' => $this->data->orderId];
    }
}
