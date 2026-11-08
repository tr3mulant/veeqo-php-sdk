<?php

namespace TremulantTech\Veeqo\Requests\KitContents;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/retrieve-bundle-content/
 */
class GetKitContent extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $kitId, protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/kits/{$this->kitId}/kit_contents/{$this->id}";
    }
}
