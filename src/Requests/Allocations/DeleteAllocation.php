<?php

namespace TremulantTech\Veeqo\Requests\Allocations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/delete-allocation/
 */
class DeleteAllocation extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $orderId, protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/allocations/{$this->id}";
    }
}
