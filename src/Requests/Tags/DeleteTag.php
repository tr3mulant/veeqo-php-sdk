<?php

namespace TremulantTech\Veeqo\Requests\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-tag/
 */
class DeleteTag extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/tags/{$this->id}";
    }
}
