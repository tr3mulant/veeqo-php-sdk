<?php

namespace TremulantTech\Veeqo\Requests\Suppliers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-supplier/
 */
class DeleteSupplier extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/suppliers/{$this->id}";
    }
}
