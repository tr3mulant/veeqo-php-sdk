<?php

namespace TremulantTech\Veeqo\Requests\Allocations;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Allocations\CreateAllocationData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/create-a-new-allocation/
 */
class CreateAllocation extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected int $orderId, protected CreateAllocationData $data) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/allocations";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['allocation' => Veeqo::normalize($this->data)];
    }
}
