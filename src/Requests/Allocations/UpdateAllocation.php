<?php

namespace TremulantTech\Veeqo\Requests\Allocations;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Allocations\UpdateAllocationData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-allocation-detail/
 */
class UpdateAllocation extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $orderId, protected int $id, protected UpdateAllocationData $data) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/allocations/{$this->id}";
    }

    /**
     * Unenveloped, as the spec documents.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return Veeqo::normalize($this->data);
    }
}
