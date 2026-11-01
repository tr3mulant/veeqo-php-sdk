<?php

namespace TremulantTech\Veeqo\Requests\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-store-detail/
 */
class GetChannel extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->id}";
    }
}
