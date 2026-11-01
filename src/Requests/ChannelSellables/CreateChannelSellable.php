<?php

namespace TremulantTech\Veeqo\Requests\ChannelSellables;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\ChannelSellables\CreateChannelSellableData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/createchannelsellable/
 */
class CreateChannelSellable extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateChannelSellableData $data) {}

    public function resolveEndpoint(): string
    {
        return '/api/v2/channel_sellables';
    }

    /**
     * Veeqo answers 415 without the JSON:API media type.
     *
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return ['Accept' => 'application/vnd.api+json', 'Content-Type' => 'application/vnd.api+json'];
    }

    /**
     * A JSON:API document with string ids, as the docs show; `channel_product_id` becomes the `channel_product` relationship.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $attributes = Veeqo::normalize($this->data);
        unset($attributes['channel_product_id']);
        $attributes['sellable_id'] = (string) $this->data->sellableId;
        $attributes['channel_id'] = (string) $this->data->channelId;
        $relationships = $this->data->channelProductId === null ? [] : [
            'relationships' => ['channel_product' => ['data' => ['type' => 'channel_product', 'id' => (string) $this->data->channelProductId]]],
        ];

        return ['data' => ['type' => 'channel_sellables', 'attributes' => $attributes, ...$relationships]];
    }
}
