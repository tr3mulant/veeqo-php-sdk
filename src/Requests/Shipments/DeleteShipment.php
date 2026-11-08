<?php

namespace TremulantTech\Veeqo\Requests\Shipments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-shipment/
 */
class DeleteShipment extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/shipments/{$this->id}";
    }
}
