<?php

namespace TremulantTech\Veeqo\Requests\Labels;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use TremulantTech\Veeqo\Enums\LabelFormat;

/**
 * @see https://developers.veeqo.com/api/operations/retrieve-shipping-labels/
 */
class GetLabel extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  list<int>  $shipmentIds
     */
    public function __construct(protected array $shipmentIds, protected LabelFormat $format) {}

    public function resolveEndpoint(): string
    {
        return "/shipping/labels{$this->format->value}";
    }

    protected function defaultQuery(): array
    {
        return ['shipment_ids' => $this->shipmentIds];
    }

    /**
     * The spec requires the format's media type as well as its extension.
     */
    protected function defaultHeaders(): array
    {
        return ['Accept' => match ($this->format) {
            LabelFormat::Pdf => 'application/pdf',
            LabelFormat::Png => 'image/png',
            LabelFormat::Jpeg => 'image/jpeg',
            LabelFormat::Zpl => 'application/zpl',
        }];
    }
}
