<?php

namespace TremulantTech\Veeqo\Requests\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-channels/
 */
class DeleteChannel extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->id}";
    }
}
