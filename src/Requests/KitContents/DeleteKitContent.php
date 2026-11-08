<?php

namespace TremulantTech\Veeqo\Requests\KitContents;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-bundle-content/
 */
class DeleteKitContent extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $kitId, protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/kits/{$this->kitId}/kit_contents/{$this->id}";
    }
}
