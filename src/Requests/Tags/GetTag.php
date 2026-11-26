<?php

namespace TremulantTech\Veeqo\Requests\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-an-tag-detail/
 */
class GetTag extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/tags/{$this->id}";
    }
}
