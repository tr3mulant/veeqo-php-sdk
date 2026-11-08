<?php

namespace TremulantTech\Veeqo\Requests\Shipping;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Shipping\PurchaseLabelsData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/purchase-shipping-labels/
 */
class PurchaseLabels extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected PurchaseLabelsData $data) {}

    public function resolveEndpoint(): string
    {
        return '/shipping/shipments';
    }

    /**
     * `carrier` sits beside the envelope, as the docs show (ADR 0010).
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $shipment = Veeqo::normalize($this->data);
        unset($shipment['carrier']);

        return ['carrier' => $this->data->carrier, 'shipment' => $shipment];
    }
}
